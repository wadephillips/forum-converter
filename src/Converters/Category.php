<?php


namespace wadelphillips\ForumConverter\Converters;


use wadelphillips\ForumConverter\Models\LegacyCategory;
use function collect;
use function dd;

class Category
{
    public static function migrate(LegacyCategory $category, array $options = [])
    {
        if (! empty($options)) {
            dd('need to handle the options!');
        }

        return true;
    }
}
