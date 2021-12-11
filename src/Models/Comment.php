<?php


namespace wadephillips\ForumConverter\Models;

use Corcel\Model\Post;

/**
 * Comment Model represents a Forum Comment in a Buddy Press installation.
 * @package wadephillips\ForumConverter\Models
 */
class Comment extends Post
{
    protected $postType = 'reply';
}
