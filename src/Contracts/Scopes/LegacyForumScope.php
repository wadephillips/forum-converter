<?php


namespace wadelphillips\ForumConverter\Contracts\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LegacyForumScope implements \Illuminate\Database\Eloquent\Scope
{
    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('forum_is_cat', '=', 'n')
            ->where('forum_parent', '>', 0);
    }
}
