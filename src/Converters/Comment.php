<?php


namespace wadelphillips\ForumConverter\Converters;

use Corcel\Model\User;
use wadelphillips\ForumConverter\Models\Topic;
use wadelphillips\ForumConverter\Models\Comment as CommentPost;
use wadelphillips\ForumConverter\Models\LegacyComment;
use function dd;

class Comment
{
    public static function migrate(LegacyComment $legacyComment, array $options = []): CommentPost
    {
        if ( !empty($options) ) {
            dd('need to handle the options!');
        }


        $comment = new CommentPost();

        $parent = Topic::hasMeta('_bbp_legacy_topic_id', $legacyComment->topic_id)
            ->get()
            ->first();

        $author = User::find($legacyComment->author_id);


        $comment = new CommentPost();

        $comment->post_author= $legacyComment->author_id;
        $comment->post_content = $legacyComment->body;
        $comment->post_content_filtered = '';
        $comment->post_title = '';
        $comment->post_excerpt = '';

        //dates
        $comment->post_date = $legacyComment->post_date_local;
        $comment->post_date_gmt = $legacyComment->post_date_UTC;
        $comment->post_modified = $legacyComment->post_date_local;
        $comment->post_modified_gmt = $legacyComment->post_date_UTC;

        //forum meta
        $comment->post_type = 'reply';
        $comment->post_parent = $parent->ID ?? 0;

        $comment->post_status = 'publish';

        $comment->comment_status = 'open';
        $comment->ping_status = 'closed';
        $comment->to_ping = '';
        $comment->pinged = '';

        $comment->save();

        //in post meta


        $comment->saveMeta([
            '_bbp_legacy_post_id' => $legacyComment->post_id,
            '_bbp_legacy_parent_id' => $legacyComment->topic_id,
            '_bbp_legacy_forum_id' => $legacyComment->forum_id,


            '_bbp_author_ip' => $legacyComment->ip_address,
            '_bbp_last_active_time' => $legacyComment->post_date_UTC,

//            '_bbp_' => $legacyComment->,
        ]);

        return $comment;
    }
}
