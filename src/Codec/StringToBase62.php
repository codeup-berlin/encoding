<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec\Gmp\HexToBase62;
use InvalidArgumentException;
use Throwable;

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
        try {
            return sodium_hex2bin(parent::decode($data));
        } catch (Throwable $e) {
            throw new InvalidArgumentException(
                'Decoding failed. Inner hex not valid. ' . $e->getMessage(),
                0,
                $e,
            );
        }
    }
}
