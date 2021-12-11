<?php


namespace wadephillips\ForumConverter\Models;

use Corcel\Model\Post;

/**
 * ForumAttachment Model represents a Forum Attachment in a Buddy Press installation.
 * @package wadephillips\ForumConverter\Models
 */
class ForumAttachment extends Post
{
    protected $postType = 'attachment';
}
