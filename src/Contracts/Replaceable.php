<?php

namespace wadelphillips\ForumConverter\Contracts;

use wadelphillips\ForumConverter\Services\ComplexReplacers\BaseReplacer;
use wadelphillips\ForumConverter\Services\ComplexReplacers\QuoteReplacer;

interface Replaceable
{

    public function process(): BaseReplacer;

    /**
     * @return string
     */
    public function getBody(): string;

}