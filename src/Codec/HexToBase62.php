<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec as EncodingStrategy;
use InvalidArgumentException;

class HexToBase62 implements EncodingStrategy
{
    /**
     * @param string $data
     * @param int $fromBase
     * @param int $toBase
     * @return string
     */
    private function gmpBaseConvert(string $data, int $fromBase, int $toBase): string
    {
        return gmp_strval(gmp_init($data, $fromBase), $toBase);
    }

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        if ('' === $data) {
            return '';
        }
        if (false === hex2bin($data)) {
            throw new InvalidArgumentException('Invalid hex string.');
        }
        return $this->gmpBaseConvert($data, 16, 62);
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        if ('' === $data) {
            return '';
        }
        return $this->gmpBaseConvert($data, 62, 16);
    }
}
