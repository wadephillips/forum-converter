<?php


namespace wadelphillips\ForumConverter\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Contracts\Scopes\LegacyForumScope;
use wadelphillips\ForumConverter\Contracts\Scopes\LegacyTopicScope;

/**
 * @property mixed title
 * @property mixed topic_date
 * @property mixed topic_date_local
 * @property mixed topic_edit_date
 * @property mixed topic_edit_local_date
 * @property mixed topic_id
 * @property mixed author_id
 * @property mixed body
 * @property mixed slug
 */
class LegacyTopic extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forum_topics';

    protected $dateFormat = 'U';

    /*
     * Global Scope
     * */

    protected static function booted()
    {
        static::addGlobalScope(new LegacyTopicScope);
    }

    /*
     * Accessors
     * */

    public function getTopicDateAttribute()
    {
        return Carbon::parse($this->topic_date);
    }

    public function getTopicDateLocalAttribute()
    {
        return Carbon::parse($this->topic_date)
            ->setTimezone('America/Los_Angeles');
    }

    public function getTopicModifiedDateAttribute()
    {
        return Carbon::parse($this->topic_edit_date);
    }

    public function getTopicModifiedDateLocalAttribute()
    {
        return Carbon::parse($this->topic_edit_date)
            ->setTimezone('America/Los_Angeles');
    }

    public function getLastPostDateAttribute()
    {
        return Carbon::parse($this->last_post_date);
    }

    public function getLastPostDateLocalAttribute()
    {
        return Carbon::parse($this->last_post_date)
            ->setTimezone('America/Los_Angeles');
    }


    public function getSlugAttribute($value)
    {
        return Str::slug($this->title);
    }
}
