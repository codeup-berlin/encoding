<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec;

readonly class ZeroHeaded implements Codec
{
    /**
     * @param int $minimalDecodedLength
     */
    public function __construct(
        private int $minimalDecodedLength,
    ) {
    }

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string
    {
        return str_pad($data, $this->minimalDecodedLength, '0', STR_PAD_LEFT);
    }
}
