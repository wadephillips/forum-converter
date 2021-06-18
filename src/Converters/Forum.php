<?php


namespace wadelphillips\ForumConverter\Converters;

use Corcel\Model\Option;
use function dd;
use wadelphillips\ForumConverter\Models\Category as Category;
use wadelphillips\ForumConverter\Models\Forum as ForumPost;
use wadelphillips\ForumConverter\Models\LegacyForum;

class Forum
{
    public static function migrate(LegacyForum $legacyForum, array $options = []): ForumPost
    {
        if (! empty($options)) {
            dd('need to handle the options!');
        }

        $status = [
            'o' => 'publish',
            'p' => 'private',
            'c' => 'hidden',
            'h' => 'draft',
        ];

        $parent = Category::hasMeta('_bbp_legacy_forum_id', $legacyForum->forum_parent)->first();

        $forum = new ForumPost();

        $forum->post_title = $legacyForum->forum_name;
        $forum->post_name = $legacyForum->slug;
        $forum->post_content = $legacyForum->forum_description;
        $forum->post_excerpt = "";
        $forum->post_content_filtered = '';
        $forum->post_author = 1;

        //dates
        $forum->post_date = '2012-01-01 00:00:00';
        $forum->post_date_gmt = '2012-01-01 08:00:00';
        $forum->post_modified = '2012-01-01 00:00:00';
        $forum->post_modified_gmt = '2012-01-01 08:00:00';

        //forum meta
        $forum->post_type = 'forum';
        $forum->post_parent = $parent->ID; // todo check
        $forum->menu_order = $legacyForum->forum_order;

        //todo Refactor to calculate post_status based on permissions of the forum would be better. Other wise will need to manually check all imported forums so as not to expose sensitive material to public
//        $forum->post_status = $status[ $legacyCategory->forum_status ];
        $forum->post_status = 'closed';

        $forum->comment_status = 'closed';
        $forum->ping_status = 'closed';
        $forum->to_ping = '';
        $forum->pinged = '';
        $forum->guid = Option::get('home') . 'forums/forum/' . $legacyForum->slug . '/';

        $forum->save();

        $forum->saveMeta([
            //_mper unauthorized settings
            '_mepr_unauthorized_message_type' => 'default',
            '_mepr_unauth_login' => 'default',
            '_mepr_unauth_excerpt_type' => 'default',
            '_mepr_unauth_excerpt_size' => '100',

            //forum meta
            '_bbp_last_active_time' => 0,

            '_bbp_forum_type' => 'forum',

            //legacy meta
            '_bbp_forum_id' => $forum->ID,
            '_bbp_forum_parent_id' => $parent->ID,//todo check

            //Counts for topics in this forum
            '_bbp_topic_count' => $forum->forum_total_topics ,
            '_bbp_reply_count' => $forum->total_posts,
            '_bbp_topic_count_hidden' => 0,


            //counts for total topics in all sub-forums
            '_bbp_total_topic_count' => $legacyForum->forum_total_topics,
            '_bbp_total_reply_count' => $legacyForum->total_posts,

        ]);

        return $forum;
    }
}
