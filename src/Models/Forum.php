<?php


namespace wadephillips\ForumConverter\Models;

use Corcel\Model\Post;

/**
 * Forum Model represents a Forum in a Buddy Press installation.
 * @package wadephillips\ForumConverter\Models
 */
class Forum extends Post
{
    protected $postType = 'forum';
}
