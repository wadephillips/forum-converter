<?php


namespace wadelphillips\ForumConverter\Models;

use Illuminate\Database\Eloquent\Model;

class LegacyComment extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forum_posts';

    public function topic()
    {
        return $this->belongsTo(LegacyTopic::class, 'topic_id', 'topic_id');
    }

    public function forum()
    {
        return $this->belongsTo(LegacyTopic::class, 'forum_id', 'forum_id');
    }
}
