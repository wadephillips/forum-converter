<?php


namespace wadephillips\ForumConverter\Models;

use Corcel\Model\Post;

/**
 * Category Model represents a Forum Category in a Buddy Press installation.
 * @package wadephillips\ForumConverter\Models
 */
class Category extends Post
{
    protected $postType = 'forum';
}
