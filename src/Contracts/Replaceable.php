<?php

namespace wadelphillips\ForumConverter\Contracts;

use wadelphillips\ForumConverter\Services\BaseReplacer;
use wadelphillips\ForumConverter\Services\QuoteReplacer;

interface Replaceable
{

    public function process(): BaseReplacer;

    /**
     * @return string
     */
    public function getBody(): string;

}