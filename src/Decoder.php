<?php

declare(strict_types=1);

namespace Codeup\Encoding;

use InvalidArgumentException;

interface Decoder
{
    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string;
}
