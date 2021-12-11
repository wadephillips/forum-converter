<?php

namespace wadephillips\ForumConverter\Contracts;

use wadephillips\ForumConverter\Services\ComplexReplacers\BaseReplacer;

interface Replaceable
{
    public function process(): BaseReplacer;

    /**
     * @return string
     */
    public function getBody(): string;
}
