<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Alphabet;
use Codeup\Encoding\Codec;
use InvalidArgumentException;

/**
 * @see https://www.php.net/manual/en/function.base-convert.php
 */
readonly class AnyToDecimal implements Codec
{
    /**
     * @param string|null $sourceAlphabet
     */
    public function __construct(private ?string $sourceAlphabet = null)
    {
        if (null !== $sourceAlphabet) {
            $chars = $this->stringToArray($sourceAlphabet);
            $charsUnique = array_unique($chars);
            if (count($chars) !== count($charsUnique)) {
                throw new InvalidArgumentException("Ambiguous alphabet: $sourceAlphabet");
            }
        }
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        $encodedValue = ltrim($data, '0');
        $leadingZeros = str_repeat('0', strlen($data) - strlen($encodedValue));

        if ('' === $encodedValue) {
            return $leadingZeros;
        }

        $toAlphabet = $this->sourceAlphabet ?? Alphabet::HEX->value;
        $toBaseChars = $this->stringToArray($toAlphabet);
        $toBaseDivisor = (string)count($toBaseChars);

        $result = '';
        // while $decValue >= $toBaseDivisor
        while (-1 < bccomp($encodedValue, $toBaseDivisor)) {
            $toBaseIndex = bcmod($encodedValue, $toBaseDivisor);
            $result = $toBaseChars[$toBaseIndex] . $result;
            $encodedValue = bcdiv($encodedValue, $toBaseDivisor);
        }
        $toBaseIndex = $encodedValue;
        $result = $leadingZeros . $toBaseChars[$toBaseIndex] . $result;

        if (null === $this->sourceAlphabet) {
            return sodium_hex2bin($result);
        } else {
            return $result;
        }
    }

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        if (null === $this->sourceAlphabet) {
            $value = sodium_bin2hex($data);
            $fromAlphabet = Alphabet::HEX->value;
        } else {
            $value = $data;
            $fromAlphabet = $this->sourceAlphabet;
        }

        $trimmedValue = ltrim($value, '0');
        $leadingZeros = str_repeat('0', strlen($value) - strlen($trimmedValue));

        if ('' === $trimmedValue) {
            return $leadingZeros;
        }

        $fromChars = $this->stringToArray($fromAlphabet);
        $fromDivisor = count($fromChars);

        $valueChars = $this->stringToArray($trimmedValue);
        $valueLength = count($valueChars);

        $result = '';
        for ($i = 1; $i <= $valueLength; $i++) {
            $valueChar = $valueChars[$i - 1];
            $fromIndexZeroBased = (string)array_search($valueChar, $fromChars);
            $result = bcadd(
                $result,
                bcmul(
                    $fromIndexZeroBased,
                    bcpow((string)$fromDivisor, (string)($valueLength - $i)),
                ),
            );
        }
        return $leadingZeros . $result;
    }

    /**
     * @param string $value
     * @return array
     */
    private function stringToArray(string $value): array
    {
        return '' === $value ? [] : str_split($value);
    }
}
