<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Gmp;

use Codeup\Encoding\Alphabet;
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
        $this->assertValidHexString($data);
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
        $this->assertValidBase62String($data);
        return $this->gmpBaseConvert($data, 62, 16);
    }

    /**
     * @param string $value
     * @return void
     */
    private function assertValidHexString(string $value): void
    {
        if (!ctype_xdigit($value)) {
            throw new InvalidArgumentException('Invalid hex string result.');
        }
    }

    /**
     * @param string $value
     * @return void
     */
    private function assertValidBase62String(string $value): void
    {
        if (!Alphabet::BASE62_GMP->isValidString($value)) {
            throw new InvalidArgumentException('Invalid base62 string result.');
        }
    }
}
