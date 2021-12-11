<?php


namespace wadephillips\ForumConverter\Contracts\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LegacyForumScope implements Scope
{
    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('board_id', '=', 1)
            ->where('forum_is_cat', '=', 'n')
            ->where('forum_parent', '>', 0);
    }
}
