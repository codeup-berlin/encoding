<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Gmp;

use Codeup\Encoding\Codec;
use InvalidArgumentException;

class IntegerToBase62 implements Codec
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
        $intValue = $this->asInteger($data);
        $sign = $intValue < 0 ? '' : '+';
        return $sign . $this->gmpBaseConvert($data, 10, 62);
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        return $this->gmpBaseConvert(ltrim($data, '+'), 62, 10);
    }

    /**
     * @param string $data
     * @return int
     */
    private function asInteger(string $data): int
    {
        $intValue = (int)$data;
        if ($data !== (string)$intValue) {
            throw new InvalidArgumentException("Not an integer: $data");
        }
        return $intValue;
    }
}
