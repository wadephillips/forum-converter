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

        /*
         * todo
         * so the topics migration works!! yay!! 🎆
         * but I see some issues, esp with the content
         * its full of crappy html-ish tags, which don't work in wp. EG [strong][/strong]
         * How do we fix that?
         * Probably going to need to iterate across the LegacyTopics and replace the bad tags urls/ strings
         *
         * Dates on the topics inside of their forum are wrong initially after import.  Just need to do a BuddyBoss Repair " Recalculate last activity in each discussion and forum"
         *
         * Also attachments and forum links are going to break.  How are we going to redirect all the old forum links....
         * */

        $topic = new TopicPost();

        $topic->post_author = $legacyTopic->author_id;
        $topic->post_content = $legacyTopic->body;
        $topic->post_content_filtered = '';//$legacyTopic->body
        $topic->post_excerpt = '';
        $topic->post_title = $legacyTopic->title;
        $topic->post_name = $legacyTopic->slug;

        //dates
        $topic->post_date = $legacyTopic->topic_date_local;
        $topic->post_date_gmt = $legacyTopic->topic_date;
        $topic->post_modified = $legacyTopic->topic_modified_date_local;
        $topic->post_modified_gmt = $legacyTopic->topic_modified_date;

//        $topic->post_ = $legacyTopic->;

        //forum meta
        $topic->post_type = 'topic';
        $topic->post_parent = $parent->ID;

        $topic->post_status = $status[ $legacyTopic->status ];

        $topic->comment_status = $status[ $legacyTopic->status ];
        $topic->ping_status = 'closed';
        $topic->to_ping = '';
        $topic->pinged = '';
        $topic->guid = Option::get('home') . 'forums/forums/discussion/' . $topic->slug . '/';

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
