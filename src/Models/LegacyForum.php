<?php


namespace wadelphillips\ForumConverter\Models;

use function array_diff;
use function explode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function trim;
use function unserialize;
use wadelphillips\ForumConverter\Contracts\Scopes\LegacyForumScope;

class LegacyForum extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forums';

    protected $primaryKey = 'forum_id';

    protected static function booted()
    {
        static::addGlobalScope(new LegacyForumScope);
    }

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
        $read = explode(
            '|',
            trim(unserialize($this->forum_permissions)[ 'can_view_forum' ], '|')
        );
        // if permissions are a subset of these groups they should be hidden
        return empty(array_diff($read, config('forum-converter.forum.legacy.privateGroups')
        ));
    }
}
