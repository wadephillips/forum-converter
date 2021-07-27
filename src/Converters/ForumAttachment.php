<?php


namespace wadelphillips\ForumConverter\Converters;

use Illuminate\Support\Str;
use function mime_content_type;
use function storage_path;
use wadelphillips\ForumConverter\Models\ForumAttachment as ForumAttachmentPost;
use wadelphillips\ForumConverter\Models\LegacyForumAttachment;

class ForumAttachment
{
    public static function migrate(LegacyForumAttachment $legacyAttachment, array $options = []): ForumAttachmentPost
    {
        //todo resume: need to get parent info, I think we can set up an if else block?  Does the attachment belong to a Topic?
        if ($legacyAttachment->parentIsTopic()) {
            $parentCommentId = 0; //Comment::hasMeta('_bbp_coment
            $parentTopic = Something;
            $parentForumId = $parentTopic->ID;
        } else {
            //todo attachment belongs to a comment so we need to set the parent ids accordingly
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

        //dates todo review the dates for accuracy
        $attachment->post_date = $legacyAttachment->post_date_local;
        $attachment->post_date_gmt = $legacyAttachment->post_date_UTC;
        $attachment->post_modified = $legacyAttachment->post_date_local;
        $attachment->post_modified_gmt = $legacyAttachment->post_date_UTC;



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

        // create wp_bp_document_meta

        //create wp_post_meta
        //legacy attachment is an actual file that we need to move from legacy storage to current storage
        //todo figure out the post_mime_type
        $attachment->post_mime_type = mime_content_type(storage_path('/file/name'));

        $attachment = new ForumAttachmentPost();

        return $attachment;
    }
}
