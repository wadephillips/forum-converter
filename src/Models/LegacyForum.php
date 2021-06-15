<?php


namespace wadelphillips\ForumConverter\Models;


use Illuminate\Database\Eloquent\Model;
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
}
