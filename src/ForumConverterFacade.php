<?php

namespace wadephillips\ForumConverter;

use Illuminate\Support\Facades\Facade;

/**
 * @see \wadephillips\ForumConverter\ForumConverter
 */
class ForumConverterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'forum-converter';
    }
}
