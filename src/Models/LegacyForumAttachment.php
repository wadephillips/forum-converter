<?php


namespace wadephillips\ForumConverter\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use wadephillips\ForumConverter\Contracts\Scopes\BoardScope;

/**
 * LegacyForumAttachment Model represents a Forum Attachment in a ExpressionEngine2 installation.
 *
 * @property mixed extension
 * @property mixed filehash
 * @property mixed attachment_date
 */
class LegacyForumAttachment extends Model
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

    public function getFullHashAttribute()
    {
        return $this->filehash . $this->extension;
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->attachment_date);
    }

    public function getDateLocalAttribute()
    {
        return Carbon::parse($this->attachment_date)
            ->setTimezone('America/Los_Angeles');
    }

    public function getModifiedDateAttribute()
    {
        return $this->getDateAttribute();
    }

    public function getModifiedDateLocalAttribute()
    {
        return $this->getDateLocalAttribute();
    }

    public function getSlugAttribute($value)
    {
        return Str::slug($this->title);
    }

    public function parentIsComment()
    {
        return $this->post_id !== 0;
    }
}
