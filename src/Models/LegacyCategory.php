<?php


namespace wadelphillips\ForumConverter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Contracts\Scopes\LegacyForumCategoryScope;
use wadelphillips\ForumConverter\Database\Factories\LegacyCategoryFactory;

/**
 * @property mixed slug
 */
class LegacyCategory extends Model
{
    use HasFactory;
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = "forums";

    public function getSlugAttribute()
    {
        return Str::slug($this->forum_name);
    }

    protected static function booted()
    {
        static::addGlobalScope(new LegacyForumCategoryScope);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new LegacyCategoryFactory();
    }
}
