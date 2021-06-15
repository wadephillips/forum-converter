<?php


namespace wadelphillips\ForumConverter\Models;


use Illuminate\Database\Eloquent\Model;

class LegacyComment extends Model
{
    /**
     * @var string
     */
    protected $connection = 'legacy';

    protected $table = 'forum_posts';
}
