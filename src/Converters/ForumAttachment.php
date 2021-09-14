<?php


namespace wadelphillips\ForumConverter\Converters;

use Exception;
use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function mime_content_type;
use const PHP_EOL;
use wadelphillips\ForumConverter\Models\Comment;
use wadelphillips\ForumConverter\Models\ForumAttachment as ForumAttachmentPost;
use wadelphillips\ForumConverter\Models\LegacyForumAttachment;
use wadelphillips\ForumConverter\Models\Topic;

class ForumAttachment
{
    public static function migrate(LegacyForumAttachment $legacyAttachment, array $options = []): ForumAttachmentPost
    {
        try {//get new parent ids of the legacy attachment
            $parentTopic = Topic::hasMeta('_bbp_legacy_topic_id', $legacyAttachment->topic_id)->first();
            $parentTopicId = $parentTopic->ID;

            if (($legacyAttachment->parentIsComment())) {
                $parentComment = Comment::hasMeta('_bbp_legacy_post_id', $legacyAttachment->post_id)
                    ->get()
                    ->first();
                $parentCommentId = $parentComment->ID;
                $parent = $parentComment;
            } else {
                $parentCommentId = 0;
                $parent = $parentTopic;
            }//Comment::hasMeta('_bbp_coment

            $parentForumId = $parentTopic->post_parent;
        } catch (Exception $e) {
            throw new Exception('There is a problem importing legacy attachment #' . $legacyAttachment->attachment_id, 0, $e);
        }


        if (! empty($options)) {
            dd('need to handle the options!');
        }

        //legacy attachment will have db record
        // create wp_post
        $attachment = new ForumAttachmentPost();

        $attachment->post_author = $legacyAttachment->member_id;
        $attachment->post_content = '';
        $attachment->post_content_filtered = '';
        $attachment->post_title = $legacyAttachment->filename;
        $attachment->post_name = Str::remove(
            $legacyAttachment->extension,
            $legacyAttachment->filename
        );
        $attachment->post_excerpt = '';

        //dates
        $attachment->post_date = $legacyAttachment->dateLocal;
        $attachment->post_date_gmt = $legacyAttachment->date;
        $attachment->post_modified = $legacyAttachment->modifiedDateLocal;
        $attachment->post_modified_gmt = $legacyAttachment->modifiedDate;


        //attachment meta
        $attachment->post_type = 'attachment';
        $attachment->post_parent = 0;

        $attachment->post_status = 'inherit';

        $attachment->comment_status = 'open';
        $attachment->ping_status = 'closed';
        $attachment->to_ping = '';
        $attachment->pinged = '';
        $yearMonthPath = Carbon::now()->year . '/' .
            Carbon::now()->format('m') . '/';

        $attachment->guid = config('app.url') .
            'app/uploads/bb_documents/' . $yearMonthPath . $legacyAttachment->filename;

        $attachment->save();

        // create wp_bp_document
        $bpDocumentId = DB::table('bp_document', )->insertGetID(
            [
                'blog_id' => 1,
                'attachment_id' => $attachment->ID,
                'user_id' => $attachment->post_author,
                'title' => $attachment->post_name,
                'folder_id' => 0,
                'group_id' => 0,
                'activity_id' => 0,
                'privacy' => 'forums',
                'date_created' => $attachment->post_date,
                'date_modified' => $attachment->post_modified,
            ]
        );

        // create wp_bp_document_meta
        DB::table('bp_document_meta')->insert([
            [ 'document_id' => $bpDocumentId, 'meta_key' => 'forum_id', 'meta_value' => $parentForumId,
            ],
            [ 'document_id' => $bpDocumentId, 'meta_key' => 'topic_id', 'meta_value' => $parentTopicId,
            ],
            [ 'document_id' => $bpDocumentId, 'meta_key' => 'reply_id', 'meta_value' => $parentCommentId,
            ],
            [ 'document_id' => $bpDocumentId, 'meta_key' => 'file_name', 'meta_value' => $legacyAttachment->filename,
            ],
            [ 'document_id' => $bpDocumentId, 'meta_key' => 'extension', 'meta_value' => $legacyAttachment->extension,
            ],
        ]);

        //create wp_post_meta
        $attachment->saveMeta([
            '_bbp_legacy_attachment_id' => $legacyAttachment->attachment_id,
            '_bbp_legacy_attachment_topic_id' => $legacyAttachment->topic_id,
            '_bbp_legacy_comment_id' => $legacyAttachment->post_id,
            'bp_document_upload' => '1',
            'bp_document_saved' => '0',
            '_wp_attached_file' => 'bb_documents/' .
                $yearMonthPath .
                $legacyAttachment->filename
            ,
        ]);

        $parent->saveMeta([ 'bp_document_ids' => $bpDocumentId ]);

        //legacy attachment is an actual file that we need to move from legacy storage to current storage
        if (Storage::disk('legacy_forum_attachment')->exists($legacyAttachment->fullHash)) {
            //get file
            $legacyPath = Storage::disk('legacy_forum_attachment')->path($legacyAttachment->fullHash);
            //rename and save to new storage location
            $storageDirectory = Carbon::now()->year . '/' .
                Carbon::now()->format('m');
            $saved = Storage::disk('wp_forum_attachment')->putFileAs($storageDirectory, new File($legacyPath), $legacyAttachment->filename);
            if ($saved) {
                //update meta bd_document_saved
                $attachment->saveMeta([ 'bp_document_saved' => 1 ]);
                //update attachment mime_type
                $newPath = Storage::disk('wp_forum_attachment')->path($saved);
                if (strlen(mime_content_type($newPath)) > 100) {
                    echo 'Check mime type for attachment with post_id #' . $attachment->ID . PHP_EOL;
                }
                $attachment->post_mime_type = Str::substr(mime_content_type($newPath), 0, 100);
                $attachment->save();
            }
        } else {
            dump('looks like the attachment doesn\'t exist') . PHP_EOL;
            dump($legacyAttachment->fullHash) . PHP_EOL;
        }

        return $attachment;
    }
}
