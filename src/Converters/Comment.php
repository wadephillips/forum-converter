<?php


namespace wadelphillips\ForumConverter\Converters;

use function dd;
use wadelphillips\ForumConverter\Models\Comment as CommentPost;
use wadelphillips\ForumConverter\Models\LegacyComment;

class Comment
{
    public static function migrate(LegacyComment $legacyComment, array $options = []): CommentPost
    {
        if (! empty($options)) {
            dd('need to handle the options!');
        }


        $comment = new CommentPost();

        //todo: write out this conversion

        return $comment;
    }
}
