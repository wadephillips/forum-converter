<?php


namespace wadelphillips\ForumConverter\Contracts\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LegacyForumCategoryScope implements Scope
{
    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('board_id', '=', 1)
            ->where('forum_is_cat', '=', 'y')
            ->whereNull('forum_parent');
    }
}
