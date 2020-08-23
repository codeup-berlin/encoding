<?php

namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;

class Dec extends BaseConv implements EncodingStrategy
{
    /**
     * @param string|null $fromBase
     */
    public function __construct(string $fromBase = null)
    {
        parent::__construct($fromBase, '0123456789');
    }
}
