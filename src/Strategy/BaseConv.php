<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;

/**
 * @see https://www.php.net/manual/en/function.base-convert.php
 */
class BaseConv implements EncodingStrategy
{
    const BASE_HEX = '0123456789abcdef';

    /**
     * @var array<string, Decimal>
     */
    private static array $decimalEncoder = [];

        /**
     * @var string|null
     */
    private ?string $sourceDictionary;

    /**
     * @var string|null
     */
    private ?string $targetDictionary;

    /**
     * @param string|null $fromBase
     * @param string|null $toBase
     */
    public function __construct(string $fromBase = null, string $toBase = null)
    {
        $this->sourceDictionary = $fromBase;
        $this->targetDictionary = $toBase;
        if (null !== $this->sourceDictionary && !isset(self::$decimalEncoder[$this->sourceDictionary])) {
            self::$decimalEncoder[$this->sourceDictionary] = new Decimal($this->sourceDictionary);
        }
        if (null !== $this->targetDictionary && !isset(self::$decimalEncoder[$this->targetDictionary])) {
            self::$decimalEncoder[$this->targetDictionary] = new Decimal($this->targetDictionary);
        }
    }

    /**
     * @see https://www.php.net/manual/en/function.base-convert.php
     * @param string $value
     * @param string $fromDictionary
     * @param string $toDictionary
     * @return string
     */
    private function convBase(string $value, string $fromDictionary, string $toDictionary): string
    {
        $decimalValue = self::$decimalEncoder[$fromDictionary]->encode($value);
        return self::$decimalEncoder[$toDictionary]->decode($decimalValue);
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
        return $this->encodeNormalized($data, $this->sourceDictionary, $this->targetDictionary);
    }

    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string
    {
        return $this->encodeNormalized($data, $this->targetDictionary, $this->sourceDictionary);
    }
}
