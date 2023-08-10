<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec;
use InvalidArgumentException;
use SodiumException;
use Throwable;

class BinToHex implements Codec
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        try {
            return mb_strtolower(sodium_bin2hex($data));
        } catch (Throwable $e) {
            throw new InvalidArgumentException('Invalid bin string. ' . $e->getMessage());
        }
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        try {
            return sodium_hex2bin($data);
        } catch (SodiumException $e) {
            throw new InvalidArgumentException('Invalid hex string. ' . $e->getMessage());
        }
    }
}
