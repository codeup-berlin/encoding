<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BinToHexTest extends TestCase
{
    /**
     * @return string[][]
     */
    public static function provideValidCases(): array
    {
        return [
            'small value' => ['bla', '626c61'],
            'stripped uuid' => [
                base64_decode('FhaTlMrtSXipMAi6M3vU3w'),
                '16169394caed4978a93008ba337bd4df'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidCases
     * @param string $plainValue
     * @param string $encodedValue
     */
    public function encode_validString(string $plainValue, string $encodedValue)
    {
        $classUnderTest = new BinToHex();
        $result = $classUnderTest->encode($plainValue);
        $this->assertSame($encodedValue, $result);
    }

    /**
     * @test
     * @dataProvider provideValidCases
     * @param string $plainValue
     * @param string $encodedValue
     */
    public function decode_validString(string $plainValue, string $encodedValue)
    {
        $classUnderTest = new BinToHex();
        $result = $classUnderTest->decode($encodedValue);
        $this->assertSame($plainValue, $result);
    }

    /**
     * @test
     */
    public function decode_invalidString()
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new BinToHex();
        $classUnderTest->decode('626c6');
    }

    /**
     * @test
     */
    public function decode_upperCase()
    {
        $classUnderTest = new BinToHex();
        $encoded = $classUnderTest->decode('626C61');
        $this->assertSame('bla', $encoded);
    }
}
