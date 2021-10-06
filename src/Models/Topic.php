<?php


namespace wadelphillips\ForumConverter\Models;

use Corcel\Model\Post;

/**
 * Topic represents a Forum Topic in a Buddy Press installation.
 * @package wadelphillips\ForumConverter\Models
 */
class Topic extends Post
{
    protected $postType = 'topic';

    protected $casts = [
        'post_date' => 'datetime',
    ];
}
