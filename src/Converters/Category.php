<?php


namespace wadelphillips\ForumConverter\Converters;


use Corcel\Model\Option;
use wadelphillips\ForumConverter\Models\Category as CategoryPost;
use wadelphillips\ForumConverter\Models\LegacyCategory;


class Category
{
    public static function migrate(LegacyCategory $legacyCategory, array $options = []): CategoryPost
    {
        if ( !empty($options) ) {
            dd('need to handle the options!');
        }
        
        $status = [
            'o' => 'publish',
            'p' => 'private',
            'c' => 'hidden',
            'h' => 'draft'
        ];

        $category = new CategoryPost();

        $category->post_title = $legacyCategory->forum_name;
        $category->post_name = $legacyCategory->slug;
        $category->post_content = $legacyCategory->forum_description;
        $category->post_excerpt = "";
        $category->post_content_filtered = '';
        $category->post_author = 1;

        //dates
        $category->post_date = '2012-01-01 00:00:00';
        $category->post_date_gmt = '2012-01-01 08:00:00';
        $category->post_modified = '2012-01-01 00:00:00';
        $category->post_modified_gmt = '2012-01-01 08:00:00';

        //categorymeta
        $category->post_type = 'forum';
        $category->post_parent = 0;
        $category->menu_order = $legacyCategory->forum_order;

        //todo Refactor to calculate post_status based on permissions of the forum would be better. Other wise will need to manually check all imported forums so as not to expose sensitive material to public
//        $category->post_status = $status[ $legacyCategory->forum_status ];
        $category->post_status = 'closed';

        $category->comment_status = 'closed';
        $category->ping_status = 'closed';
        $category->to_ping = '';
        $category->pinged = '';
        $category->guid = Option::get('home') . 'forums/forum/' . $legacyCategory->slug . '/';

        $category->save();

        $category->saveMeta([
            //_mper unauthorized settings
            '_mepr_unauthorized_message_type' => 'default',
            '_mepr_unauth_login' => 'default',
            '_mepr_unauth_excerpt_type' => 'default',
            '_mepr_unauth_excerpt_size' => '100',

            //category meta
            '_bbp_last_active_time' => 0,

            '_bbp_forum_type' => 'category',

            //legacy meta
            '_bbp_forum_id' => $legacyCategory->forum_id,
            '_bbp_forum_parent_id' => 0,

            //Counts for topics in this forum
            '_bbp_topic_count' => 0, //$category->forum_total_topics ,
            '_bbp_reply_count' => 0, //$category->total_posts,
            '_bbp_topic_count_hidden' => 0,


            //counts for total topics in all sub-forums
            '_bbp_total_topic_count' => $legacyCategory->forum_total_topics,
            '_bbp_total_reply_count' => $legacyCategory->total_posts,

        ]);

        return $category;
    }
}
