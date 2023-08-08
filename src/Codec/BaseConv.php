<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Alphabet;
use Codeup\Encoding\Codec;

/**
 * @see https://www.php.net/manual/en/function.base-convert.php
 */
class BaseConv implements Codec
{
    /**
     * @var array<string, AnyToDecimal>
     */
    private static array $decimalEncoder = [];

        /**
     * @var string|null
     */
    private ?string $sourceAlphabet;

    /**
     * @var string|null
     */
    private ?string $targetAlphabet;

    /**
     * @param string|null $fromBase
     * @param string|null $toBase
     */
    public function __construct(?string $fromBase = null, ?string $toBase = null)
    {
        $this->sourceAlphabet = $fromBase;
        $this->targetAlphabet = $toBase;
        if (null !== $this->sourceAlphabet && !isset(self::$decimalEncoder[$this->sourceAlphabet])) {
            self::$decimalEncoder[$this->sourceAlphabet] = new AnyToDecimal($this->sourceAlphabet);
        }
        if (null !== $this->targetAlphabet && !isset(self::$decimalEncoder[$this->targetAlphabet])) {
            self::$decimalEncoder[$this->targetAlphabet] = new AnyToDecimal($this->targetAlphabet);
        }
        if ((null === $this->sourceAlphabet || null === $this->targetAlphabet) &&
            !isset(self::$decimalEncoder[Alphabet::BASE_HEX->value])
        ) {
            self::$decimalEncoder[Alphabet::BASE_HEX->value] = new AnyToDecimal(Alphabet::BASE_HEX->value);
        }
    }

    /**
     * @see https://www.php.net/manual/en/function.base-convert.php
     * @param string $value
     * @param string $fromAlphabet
     * @param string $toAlphabet
     * @return string
     */
    private function convBase(string $value, string $fromAlphabet, string $toAlphabet): string
    {
        $decimalValue = self::$decimalEncoder[$fromAlphabet]->encode($value);
        return self::$decimalEncoder[$toAlphabet]->decode($decimalValue);
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
            $fromBase = Alphabet::BASE_HEX->value;
        }
        if (null === $toBase) {
            $toBase = Alphabet::BASE_HEX->value;
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
        return $this->encodeNormalized($data, $this->sourceAlphabet, $this->targetAlphabet);
    }

    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string
    {
        return $this->encodeNormalized($data, $this->targetAlphabet, $this->sourceAlphabet);
    }
}
