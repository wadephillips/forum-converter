<?php

namespace wadelphillips\ForumConverter;

use Illuminate\Support\Facades\Facade;

/**
 * @see \wadelphillips\ForumConverter\ForumConverter
 */
class ForumConverterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ee2-forum-to-bbp';
    }
}
