<?php


namespace wadelphillips\ForumConverter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use wadelphillips\ForumConverter\Contracts\Scopes\LegacyForumCategoryScope;
use wadelphillips\ForumConverter\Database\Factories\LegacyCategoryFactory;
use function array_diff;
use function explode;
use function trim;
use function unserialize;

/**
 * @property mixed slug
 */
class LegacyCategory extends Model
{
    use HasFactory;

    protected $connection = 'legacy';

    protected $table = "forums";

    protected $primaryKey = 'forum_id';

    protected static function booted()
    {
        //register a global scope for legacy forums
        static::addGlobalScope(new LegacyForumCategoryScope);
    }

    /**
     * Return a slugified version of the forum name
     * @return string
     */
    public function getSlugAttribute()
    {
        return Str::slug($this->forum_name);
    }

    /*
     * Look at legacy permissions and decide if the category should be hidden
     */
    public function shouldHide(): bool
    {

        //pull permissions out into an array
        $read = explode('|',
            trim( unserialize($this->forum_permissions)['can_view_forum'], '|')
        );
        // if permissions are a subset of these groups they should be hidden
        return empty( array_diff($read, ['6','9','11','14']) );
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
