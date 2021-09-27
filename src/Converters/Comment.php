<?php


namespace wadelphillips\ForumConverter\Converters;

use Illuminate\Support\Facades\Cache;
use wadelphillips\ForumConverter\Models\Comment as CommentPost;
use wadelphillips\ForumConverter\Models\LegacyComment;
use wadelphillips\ForumConverter\Models\Topic;

class Comment
{
    public static function migrate(LegacyComment $legacyComment, array $options = []): CommentPost
    {
        if (! empty($options)) {
            dd('need to handle the options!');
        }


        $comment = new CommentPost();

        $parent = Cache::remember('legacy.topic.' . $legacyComment->topic_id, 600, function () use ($legacyComment) {
            return Topic::hasMeta('_bbp_legacy_topic_id', $legacyComment->topic_id)
                ->get()
                ->first();
        });


        $comment = new CommentPost();

        $comment->post_author = $legacyComment->author_id;
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


//
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
