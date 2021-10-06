<?php


namespace wadelphillips\ForumConverter\Models;

use Corcel\Model\Post;

/**
 * Comment Model represents a Forum Comment in a Buddy Press installation.
 * @package wadelphillips\ForumConverter\Models
 */
class Comment extends Post
{
    protected $postType = 'reply';
}
