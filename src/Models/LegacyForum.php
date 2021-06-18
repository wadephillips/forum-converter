<?php


namespace wadelphillips\ForumConverter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Contracts\Scopes\LegacyForumScope;

class LegacyForum extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forums';

    protected static function booted()
    {
        static::addGlobalScope(new LegacyForumScope);
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->forum_name);
    }

}
