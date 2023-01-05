<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;
use InvalidArgumentException;

class Decimal implements EncodingStrategy
{
    /**
     * @param string|null $sourceDictionary
     */
    public function __construct(
        private readonly ?string $sourceDictionary = null,
    ) {
        if (null !== $sourceDictionary) {
            $chars = $this->stringToArray($sourceDictionary);
            $charsUnique = array_unique($chars);
            if (count($chars) !== count($charsUnique)) {
                throw new InvalidArgumentException("Ambiguous dictionary: $sourceDictionary");
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
        $decValue = ltrim($data, '0');
        $leadingZeros = str_repeat('0', strlen($data) - strlen($decValue));

        if ('' === $decValue) {
            return $leadingZeros;
        }

        $toDictionary = $this->sourceDictionary ?? self::BASE_HEX;
        $toBaseChars = $this->stringToArray($toDictionary);
        $toBaseDivisor = (string)count($toBaseChars);

        $result = '';
        // while $decValue >= $toBaseDivisor
        while (-1 < bccomp($decValue, $toBaseDivisor)) {
            $toBaseIndex = bcmod($decValue, $toBaseDivisor);
            $result = $toBaseChars[$toBaseIndex] . $result;
            $decValue = bcdiv($decValue, $toBaseDivisor);
        }
        $toBaseIndex = $decValue;
        $result = $leadingZeros . $toBaseChars[$toBaseIndex] . $result;

        if (null === $this->sourceDictionary) {
            return hex2bin($result);
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
        if (null === $this->sourceDictionary) {
            $value = bin2hex($data);
            $fromDictionary = self::BASE_HEX;
        } else {
            $value = $data;
            $fromDictionary = $this->sourceDictionary;
        }

        $trimmedValue = ltrim($value, '0');
        $leadingZeros = str_repeat('0', strlen($value) - strlen($trimmedValue));

        if ('' === $trimmedValue) {
            return $leadingZeros;
        }

        $fromChars = $this->stringToArray($fromDictionary);
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
