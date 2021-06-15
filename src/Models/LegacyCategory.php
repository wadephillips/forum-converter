<?php


namespace wadelphillips\ForumConverter\Models;



use Illuminate\Database\Eloquent\Model;
use wadelphillips\ForumConverter\Contracts\Scopes\LegacyForumCategoryScope;

class LegacyCategory extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = "forums";

    protected static function booted()
    {
        static::addGlobalScope(new LegacyForumCategoryScope);
    }
}
