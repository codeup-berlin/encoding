<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use PHPUnit\Framework\TestCase;

class AnyToDecimalTest extends TestCase
{
    /**
     * @return array
     */
    public static function provideValidCases(): array
    {
        return [
            'octal (base8) - 7' => [
                '7', '01234567', '7'
            ],
            'octal (base8) - 8' => [
                '10', '01234567', '8'
            ],
            'octal (base8)' => [
                '173', '01234567', '123'
            ],
            'undecimal (base11) using "Christo" as alphabet' => [
                'rhtitiCtrohhisshhCssCotthitsoitC', 'Christo', '355927353784509896715106760'
            ],
            'hex (base16) - small number' => [
                '09', '0123456789ABCDEF', '09'
            ],
            'hex (base16) - zero' => [
                '00', '0123456789ABCDEF', '00'
            ],
            'hex (base16) - empty' => [
                '', '0123456789ABCDEF', ''
            ],
            'binary - leading zero' => [
                hex2bin('06169394caed4978a93008ba337bd4df'), null, '08092591808379884713638960484539159775'
            ],
            'hex (base16) - long number, leading zero hex' => [
                '06169394caed4978a93008ba337bd4df', '0123456789abcdef', '08092591808379884713638960484539159775'
            ],
            'uuid' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31',
                '0123456789abcdef-',
                '184822225897852159683113138063168215774300634',
            ],
            'short id (ASCII without zero)' => [
                'RFBnBHD9yDk5EGAzI53BmYz9D',
                '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                '184822225897852159683113138063168215774300634',
            ],
            'short id (ASCII without zero nor O)' => [
                'f1KGlCuul4Djx2dFfeJjah2IF',
                '123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                '184822225897852159683113138063168215774300634',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidCases
     * @param string $baseValue
     * @param string|null $alphabet
     * @param string $expectedDecimalValue
     */
    public function encode_valid(string $baseValue, ?string $alphabet, string $expectedDecimalValue)
    {
        $classUnderTest = new AnyToDecimal($alphabet);
        $result = $classUnderTest->encode($baseValue);
        $this->assertSame($expectedDecimalValue, $result);
    }

    /**
     * @test
     * @dataProvider provideValidCases
     */
    public function decode_valid(string $expectedBaseValue, ?string $alphabet, string $decimalValue)
    {
        $classUnderTest = new AnyToDecimal($alphabet);
        $result = $classUnderTest->decode($decimalValue);
        $this->assertSame($expectedBaseValue, $result);
    }

    /**
     * @return array
     */
    public static function provideLeadingZeroCases(): array
    {
        return [
            'hex (base16)' => [
                '0a', '0123456789abcdef'
            ],
            'octal (base8)' => [
                '0420', '01234567'
            ],
            'uuid' => [
                '0f15e4f1-37e0-4fb7-a840-eca043107a31',
                '0123456789abcdef-',
            ],
            'nil uuid' => [
                '00000000-0000-0000-0000-000000000000',
                '0123456789abcdef-',
            ],
            'short id' => [
                '0FBnBHD9yDk5EGAzI53BmYz9D',
                '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideLeadingZeroCases
     * @param string $baseValue
     * @param string|null $alphabet
     */
    public function encodeDecode_leadingZero(
        string $baseValue,
        ?string $alphabet,
    ) {
        $classUnderTest = new AnyToDecimal($alphabet);
        $encoded = $classUnderTest->encode($baseValue);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame($baseValue, $decoded);
    }

    /**
     * @return array
     */
    public static function provideEdgeCases(): array
    {
        return [
            '0 > hex 0' => ['0', '0123456789abcdef'],
            '9 > hex 9' => ['9', '0123456789abcdef'],
            '10 > hex a' => ['10', '0123456789abcdef'],
            '15 > hex f' => ['10', '0123456789abcdef'],
            '16 > hex 1f' => ['16', '0123456789abcdef'],
            '19 > hex 4f' => ['19', '0123456789abcdef'],
            '24 > hex 9f' => ['24', '0123456789abcdef'],
            '25 > hex af' => ['25', '0123456789abcdef'],
            '30 > hex ff' => ['30', '0123456789abcdef'],
            '31 > hex 1ff' => ['31', '0123456789abcdef'],
            'stripped nil uuid leading 1' => ['10000000000000000000000000000000000', '0123456789abcdef'],
            'stripped nil uuid padded 1' => ['10000000000000000000000000000000001', '0123456789abcdef'],
        ];
    }

    /**
     * @test
     * @dataProvider provideEdgeCases
     */
    public function decodeEncode_edgeCase(string $decimalValue, string $alphabet)
    {
        $classUnderTest = new AnyToDecimal($alphabet);

        $hexValue = $classUnderTest->decode($decimalValue);
        $encoded = $classUnderTest->encode($hexValue);

        $this->assertSame($decimalValue, $encoded);
    }
}
