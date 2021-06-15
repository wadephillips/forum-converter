<?php


namespace wadelphillips\ForumConverter\Models;

use Corcel\Model\Post;

class Topic extends Post
{
    protected $postType = 'topic';
}
