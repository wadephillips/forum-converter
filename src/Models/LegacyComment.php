<?php


namespace wadelphillips\ForumConverter\Models;

use Carbon\Carbon;
use Corcel\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Contracts\Scopes\BoardScope;

/**
 * @property mixed topic_id
 * @property mixed author_id
 * @property mixed ip_address
 * @property mixed body
 */
class LegacyComment extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forum_posts';

    protected $primaryKey = 'post_id';


    /*
     * Relationships
     * */
    public function topic()
    {
        return $this->belongsTo(LegacyPost::class, 'topic_id', 'topic_id');
    }

    public function forum()
    {
        return $this->belongsTo(LegacyPost::class, 'forum_id', 'forum_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    /*
     * Register a Global Scope to limit to posts on a specific board
     * */

    protected static function booted()
    {
        static::addGlobalScope(new BoardScope);
    }

    /*
     * Date Mutators for comment if needed
     */
    public function getPostDateUTCAttribute()
    {
        return Carbon::parse($this->post_date);
    }

    public function getPostDateLocalAttribute()
    {
        return Carbon::parse($this->post_date)
            ->setTimezone('America/Los_Angeles');
    }

    public function getPostModifiedDateAttribute()
    {
        return Carbon::parse($this->post_edit_date);
    }

    public function getPostModifiedDateLocalAttribute()
    {
        return Carbon::parse($this->post_edit_date)
            ->setTimezone('America/Los_Angeles');
    }

}
