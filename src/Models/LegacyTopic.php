<?php


namespace wadelphillips\ForumConverter\Models;


use Illuminate\Database\Eloquent\Model;

class LegacyTopic extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forum_topics';
}
