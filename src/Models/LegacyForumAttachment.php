<?php


namespace wadelphillips\ForumConverter\Models;


use Carbon\Carbon;
use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Contracts\Scopes\BoardScope;

class LegacyForumAttachment
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forum_attachments';

    protected $primaryKey = 'attachment_id';

    protected $dateFormat = 'U';

    /*
     * Global Scope
     * */

    protected static function booted()
    {
        static::addGlobalScope(new BoardScope);
    }

    /*
     * Accessors
     * */

    public function getDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getDateLocalAttribute()
    {
        return Carbon::parse($this->topic_date)
            ->setTimezone('America/Los_Angeles');
    }

    public function getModifiedDateAttribute()
    {
        return Carbon::parse($this->topic_edit_date);
    }

    public function getModifiedDateLocalAttribute()
    {
        return Carbon::parse($this->topic_edit_date)
            ->setTimezone('America/Los_Angeles');
    }


    public function getSlugAttribute($value)
    {
        return Str::slug($this->title);
    }
}
