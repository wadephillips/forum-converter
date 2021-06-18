<?php


namespace wadelphillips\ForumConverter\Converters;


use Corcel\Model\Option;
use wadelphillips\ForumConverter\Models\Forum;
use wadelphillips\ForumConverter\Models\LegacyTopic;
use wadelphillips\ForumConverter\Models\Topic as TopicPost;
use function dd;

class Topic
{
    public static function migrate(LegacyTopic $legacyTopic, array $options = []): TopicPost
    {
        if (! empty($options)) {
            dd('need to handle the options!');
        }

        $parent = Forum::hasMeta('_bbp_legacy_forum_id', $legacyTopic->forum_id);

        $status = [
            'o' => 'open', //todo are these the correct values for wp?
            'c' => 'closed'
        ];


        $topic = new TopicPost();

        $topic->post_author = $legacyTopic->author_id;
        $topic->post_content = $legacyTopic->body;
        $topic->post_title = $legacyTopic->title;
        $topic->post_name = $legacyTopic->slug;
        $topic->post_parent = $parent->ID;//todo Forumvia meta

        //dates
        $topic->post_date = $legacyTopic->topic_date_local;
        $topic->post_date_gmt = $legacyTopic->topic_date;
        $topic->post_modified = $legacyTopic->topic_edit_date;
        $topic->post_modified_gmt = $legacyTopic->topic_edit_local_date;

//        $topic->post_ = $legacyTopic->;

        //todo ****
        //forum meta
        $topic->post_type = 'topic';
        $topic->post_parent = $parent->ID; // todo check
//        $topic->menu_order = $legacyTopic->forum_order; todo not sure if we need this

        $topic->post_status = $status[ $legacyTopic->status ];

        $topic->comment_status = 'closed';  //Todo resume  comments should be open, do we need to set this?
        $topic->ping_status = 'closed';
        $topic->to_ping = '';
        $topic->pinged = '';
//        $topic->guid = Option::get('home') . 'forums/forum/' . $legacyForum->slug . '/' todo??

        //todo end ***


        $topic->save();

        //in post meta
        $topic->saveMeta([
            '_bbp_legacy_topic_id' => $legacyTopic->topic_id,

            '_bbp_author_ip' => $legacyTopic->ip_address,
            '_bbp_sticky_status' => $legacyTopic->sticky,//todo check the sticky status callback to make sure we're getting good data
            '_bbp_last_active_time' => $legacyTopic->topic_edit_date,

//            '_bbp_' => $legacyTopic->,
        ]);

        return $topic;
    }
}
