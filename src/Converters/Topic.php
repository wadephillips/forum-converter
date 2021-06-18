<?php


namespace wadelphillips\ForumConverter\Converters;


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


        $topic = new TopicPost();
        
        return $topic;
    }
}
