<?php


namespace wadelphillips\ForumConverter\Converters;

use Corcel\Model\Option;
use function dd;
use wadelphillips\ForumConverter\Models\Forum;
use wadelphillips\ForumConverter\Models\LegacyTopic;
use wadelphillips\ForumConverter\Models\Topic as TopicPost;

class Topic
{
    public static function migrate(LegacyTopic $legacyTopic, array $options = []): TopicPost
    {
        if (! empty($options)) {
            dd('need to handle the options!');
        }

        $parent = Forum::hasMeta('_bbp_legacy_forum_id', $legacyTopic->forum_id)
            ->get()
            ->first();

        //look up table for translating the status of a topic
        $status = [
            'o' => 'publish',
            'c' => 'closed',
        ];

        //look up table for translating stickiness
        $sticky = [
            'y' => 'sticky',
            'n' => 'normal',
        ];

        $topic = new TopicPost();

        $topic->post_author = $legacyTopic->author_id;
        $topic->post_content = $legacyTopic->body;
        $topic->post_content_filtered = $legacyTopic->body;
        //todo we need an excerpt
        $topic->post_excerpt = '';
        $topic->post_title = $legacyTopic->title;
        $topic->post_name = $legacyTopic->slug;
        $topic->post_parent = $parent->ID;//todo Forum via meta

        //dates
        $topic->post_date = $legacyTopic->topic_date_local;
        $topic->post_date_gmt = $legacyTopic->topic_date;
        $topic->post_modified = $legacyTopic->topic_modified_date_local;
        $topic->post_modified_gmt = $legacyTopic->topic_modified_date;

//        $topic->post_ = $legacyTopic->;

        //forum meta
        $topic->post_type = 'topic';
        $topic->post_parent = $parent->ID;
//        $topic->menu_order = $legacyTopic->forum_order; todo not sure if we need this

        $topic->post_status = $status[ $legacyTopic->status ];

        $topic->comment_status = $status[ $legacyTopic->status ];
        $topic->ping_status = 'closed';
        $topic->to_ping = '';
        $topic->pinged = '';
//        $topic->guid = Option::get('home') . 'forums/forum/' . $legacyForum->slug . '/' todo??



        $topic->save();

        //in post meta

        $topic->saveMeta([
            '_bbp_legacy_topic_id' => $legacyTopic->topic_id,

            '_bbp_author_ip' => $legacyTopic->ip_address,
            '_bbp_sticky_status' => $sticky[$legacyTopic->sticky],
            '_bbp_last_active_time' => $legacyTopic->topic_edit_date,

//            '_bbp_' => $legacyTopic->,
        ]);

        return $topic;
    }
}
