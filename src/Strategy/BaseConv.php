<?php

namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;

/**
 * @see https://www.php.net/manual/en/function.base-convert.php
 */
class BaseConv implements EncodingStrategy
{
    const BASE_HEX = '0123456789abcdef';
    const BASE_DEC = '0123456789';

    /**
     * @var string|null
     */
    private $fromBase;

    /**
     * @var string|null
     */
    private $toBase;

    /**
     * @param string|null $fromBase
     * @param string|null $toBase
     */
    public function __construct(string $fromBase = null, string $toBase = null)
    {
        $this->fromBase = $fromBase;
        $this->toBase = $toBase;
    }

    /**
     * @param string $base
     * @return bool
     */
    private function isHexBase(string $base): bool
    {
        return strtolower($base) === self::BASE_HEX;
    }

    /**
     * @param string $base
     * @return bool
     */
    private function isDecBase(string $base): bool
    {
        return $base === self::BASE_DEC;
    }

    /**
     * @param string $value
     * @param string $base
     * @return string
     */
    private function dec(string $value, string $base): string
    {
        if ($this->isDecBase($base)) {
            return $value;
        }
        $baseChars = str_split($base, 1);
        $baseLen = count($baseChars);
        $valueChars = str_split($value, 1);
        $valueLength = count($valueChars);
        $result = '0';
        for ($i = 1; $i <= $valueLength; $i++) {
            $result = bcadd(
                $result,
                bcmul(
                    array_search($valueChars[$i - 1], $baseChars),
                    bcpow($baseLen, $valueLength - $i)
                )
            );
        }
        return $result;
    }

    /**
     * @see https://www.php.net/manual/en/function.base-convert.php
     * @param string $value
     * @param string $fromBase
     * @param string $toBase
     * @return string
     */
    private function convBase(string $value, string $fromBase, string $toBase): string
    {
        if ('' === $value || $fromBase === $toBase) {
            return $value;
        }
        $toBaseChars = str_split($toBase, 1);;
        $toBaseLen = count($toBaseChars);
        $decValue = $this->dec($value, $fromBase);
        if ($decValue < $toBaseLen) {
            return $toBaseChars[$decValue];
        }
        $result = '';
        while ($decValue !== '0') {
            $result = $toBaseChars[bcmod($decValue, $toBaseLen)] . $result;
            $decValue = bcdiv($decValue, $toBaseLen, 0);
        }
        // fix hex leading zero
        if ($this->isHexBase($toBase) && strlen($result) % 2) {
            $result = '0' . $result;
        }
        return $result;
    }

    /**
     * @param string $data
     * @param string|null $fromBase
     * @param string|null $toBase
     * @return false|string
     */
    private function encodeNormalized(string $data, ?string $fromBase, ?string $toBase)
    {
        if (null === $fromBase) {
            $data = bin2hex($data);
            $fromBase = self::BASE_HEX;
        }
        if (null === $toBase) {
            $toBase = self::BASE_HEX;
            $result = $this->convBase($data, $fromBase, $toBase);
            return hex2bin($result);
        } else {
            return $this->convBase($data, $fromBase, $toBase);
        }
    }

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        return $this->encodeNormalized($data, $this->fromBase, $this->toBase);
    }

    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string
    {
        return $this->encodeNormalized($data, $this->toBase, $this->fromBase);
    }
}
