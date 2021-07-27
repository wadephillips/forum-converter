<?php


namespace wadelphillips\ForumConverter\Converters;


use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Models\Comment;
use wadelphillips\ForumConverter\Models\ForumAttachment as ForumAttachmentPost;
use wadelphillips\ForumConverter\Models\LegacyForumAttachment;
use wadelphillips\ForumConverter\Models\Topic;
use function mime_content_type;
use function storage_path;

class ForumAttachment
{
    public static function migrate(LegacyForumAttachment $legacyAttachment, array $options = []): ForumAttachmentPost
    {
        //get new parent ids of the legacy attachment
        $parentCommentId = ($legacyAttachment->parentIsComment()) ?
            Comment::hasMeta('_bbp_legacy_post_id', $legacyAttachment->post_id)
                ->get()
                ->first()
                ->ID
            : 0; //Comment::hasMeta('_bbp_coment


        $parentTopic = Topic::hasMeta('_bpp_legacy_topic_id', $legacyAttachment->topic_id);
        $parentTopicId = $parentTopic->ID;
        $parentForumId = $parentTopic->post_parent;


        if ( !empty($options) ) {
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

        $attachment->save();

        // create wp_bp_document
        $bpDocumentId = DB::table('bp_document',)->insertGetID(
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
                'date_modified' => $attachment->post_modified
            ]
        );

        // create wp_bp_document_meta
        DB::table('bp_document_meta')->insert([
                [ 'document_id' => $bpDocumentId, 'meta_key' => 'forum_id', 'meta_value' => $parentForumId
                ],
                [ 'document_id' => $bpDocumentId, 'meta_key' => 'topic_id', 'meta_value' => $parentTopicId
                ],
                [ 'document_id' => $bpDocumentId, 'meta_key' => 'reply_id', 'meta_value' => $parentCommentId
                ],
                [ 'document_id' => $bpDocumentId, 'meta_key' => 'file_name', 'meta_value' => $attachment->filename
                ],
                [ 'document_id' => $bpDocumentId, 'meta_key' => 'extension', 'meta_value' => $legacyAttachment->extension
                ],
            ]);

        //create wp_post_meta
        $attachment->saveMeta([
            '_bbp_legacy_attachment_id' => $legacyAttachment->attachment_id,
            '_bbp_legacy_topic_id' => $legacyAttachment->topic_id,
            '_bbp_legacy_comment_id' => $legacyAttachment->comment_id,
            'bp_document_upload' => '1',
            'bp_document_saved' => '0',
            '_wp_attached_file' => 'bb_documents/' .
                Carbon::now()->year. '/' .
                Carbon::now()->month. '/' .
                $attachment->filename
            ,
            'bp_document_ids' => $bpDocumentId
        ]);

        //legacy attachment is an actual file that we need to move from legacy storage to current storage
        if (Storage::disk('legacy_forum_attachment')->get($legacyAttachment->filehash)) {
            //get file
            $file = Storage::disk('legacy_forum_attachment')->get($legacyAttachment->filehash);
            //rename and save to new storage location
            $storageDirectory = Carbon::now()->year. '/' .
                Carbon::now()->month;
            $saved = Storage::disk('wp_forum_attachment')->putFileAs( $storageDirectory ,new File($legacyAttachment->filename), $legacyAttachment->filename);
            //update meta bd_document_saved
            if ($saved) {
                $attachment->saveMeta(['bp_document_saved' => 1]);
            }
            //update attachment mime_type
            $attachment->post_mime_type = mime_content_type($saved);
            $attachment->save();


        }

        return $attachment;
    }
}
