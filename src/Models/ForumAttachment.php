<?php


namespace wadelphillips\ForumConverter\Models;

use Corcel\Model\Post;

/**
 * ForumAttachment Model represents a Forum Attachment in a Buddy Press installation.
 * @package wadelphillips\ForumConverter\Models
 */
class ForumAttachment extends Post
{
    protected $postType = 'attachment';

}
