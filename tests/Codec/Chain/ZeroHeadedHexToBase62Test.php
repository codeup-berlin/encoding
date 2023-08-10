<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Chain;

use PHPUnit\Framework\TestCase;

class ZeroHeadedHexToBase62Test extends TestCase
{
    /**
     * @return array[]
     */
    public static function provideValues(): array
    {
        return [
            'simple' => [3, 'abc', 'abc'],
            'leading even zeros' => [6, '0000cd', '0000cd'],
            'leading odd zeros' => [6, '000bcd', '000bcd'],
            'source too short' => [6, 'abc', '000abc'],
            'source too long' => [3, 'abcde', 'abcde'],
            'tailing zeros' => [6, 'ab0000', 'ab0000'],
            'leading even zeros with tailing zero' => [6, '0000c0', '0000c0'],
            'leading odd zeros with tailing zero' => [6, '000bc0', '000bc0'],
        ];
    }

    /**
     * @test
     * @dataProvider provideValues
     * @param string $value
     */
    public function encodeDecode_valid(int $length, string $value, string $expectedDecoded)
    {
        // prepare
        $classUnderTest = new ZeroHeadedHexToBase62($length);

        // test
        $encoded = $classUnderTest->encode($value);
        $decoded = $classUnderTest->decode($encoded);

        // verify
        $this->assertSame($expectedDecoded, $decoded);
    }
}
