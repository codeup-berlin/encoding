<?php

declare(strict_types=1);

namespace Codeup\Encoding\Compact;

use Codeup\Encoding\Codec;
use Codeup\Encoding\Codec\Chain\UuidToBase62;
use Codeup\Encoding\Codec\IntegerToBase62;
use Codeup\Encoding\Codec\StringToBase62;
use Codeup\Encoding\Compact;

readonly class Base62 implements Compact
{
    /**
     * @param Codec $stringToBase62
     * @param Codec $integerToBase62
     */
    public function __construct(
        private Codec $stringToBase62,
        private Codec $integerToBase62
    ) {
    }

    /**
     * @return self
     */
    public static function makeBasicCompact(): self
    {
        return new self(new StringToBase62(), new IntegerToBase62());
    }

    /**
     * @return self
     */
    public static function makeUuidCompact(): self
    {
        return new self(new UuidToBase62(), new IntegerToBase62());
    }

    /**
     * @param int|string $value
     * @return int|string
     */
    public function compact(int|string $value): int|string
    {
        return is_string($value) ? $this->stringEncode($value) : (abs($value) < 1000 ? $value : $this->integerEncode($value));
    }

    /**
     * @param int|string $value
     * @return int|string
     */
    public function decompact(int|string $value): int|string
    {
        return is_int($value)
            ? $value
            : (
                str_starts_with($value, '+') || str_starts_with($value, '-')
                ? $this->integerDecode($value)
                : $this->stringDecode($value)
            );
    }

    /**
     * @param int $value
     * @return string
     */
    private function integerEncode(int $value): string
    {
        return $this->integerToBase62->encode((string)$value);
    }

    /**
     * @param string $value
     * @return int
     */
    private function integerDecode(string $value): int
    {
        return (int)$this->integerToBase62->decode($value);
    }

    /**
     * @param string $value
     * @return string
     */
    private function stringEncode(string $value): string
    {
        return $this->stringToBase62->encode($value);
    }

    /**
     * @param string $value
     * @return string
     */
    private function stringDecode(string $value): string
    {
        return $this->stringToBase62->decode($value);
    }
}
