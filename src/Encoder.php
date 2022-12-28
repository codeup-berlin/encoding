<?php

declare(strict_types=1);

namespace Codeup\Encoding;

interface Encoder
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string;
}
