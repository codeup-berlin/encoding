<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec\Gmp\HexToBase62;
use InvalidArgumentException;

class StringToBase62 extends HexToBase62
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        return parent::encode(bin2hex($data));
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        $decoded = hex2bin(parent::decode($data));
        if (false === $decoded) {
            throw new InvalidArgumentException('Decoding failed. Inner hex not valid.');
        }
        return $decoded;
    }
}
