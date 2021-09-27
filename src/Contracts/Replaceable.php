<?php

namespace wadelphillips\ForumConverter\Contracts;

use wadelphillips\ForumConverter\Services\ComplexReplacers\BaseReplacer;

interface Replaceable
{
    public function process(): BaseReplacer;

    /**
     * @return string
     */
    public function getBody(): string;
}
