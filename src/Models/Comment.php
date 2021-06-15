<?php


namespace wadelphillips\ForumConverter\Models;

use Corcel\Model\Post;

class Comment extends Post
{
    protected $postType = 'comment';
}
