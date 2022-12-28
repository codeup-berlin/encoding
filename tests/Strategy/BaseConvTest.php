<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use PHPUnit\Framework\TestCase;

class BaseConvTest extends TestCase
{
    /**
     * @return array
     */
    public function provideValidCases(): array
    {
        return [
            'from decimal (base10) to octal (base8)' => [
                '123', '0123456789', '01234567',
                '173'
            ],
            'from hexadecimal (base16) to binary (base2)' => [
                '70B1D707EAC2EDF4C6389F440C7294B51FFF57BB', '0123456789ABCDEF', '01',
                '111000010110001110101110000011111101010110000101110110111110100110001100011100010011111010001000000110001110010100101001011010100011111111111110101011110111011'
            ],
            'from senary (base6) to hexadecimal (base16)' => [
                '1324523453243154324542341524315432113200203012', '012345', '0123456789abcdef',
                '1f9881bad10454a8c23a838ef00f50'
            ],
            'from decimal (base10) to undecimal (base11) using "Christo" as the numbers' => [
                '355927353784509896715106760','0123456789','Christo',
                'rhtitiCtrohhisshhCssCotthitsoitC'
            ],
            'from octodecimal (base18) using "0123456789aAbBcCdD" as the numbers to undecimal (base11) using "~!@#$%^&*()" as the numbers' => [
                '1C238Ab97132aAC84B72','0123456789aAbBcCdD', '~!@#$%^&*()',
                '!%~!!*&!~^!!&(&!~^@#@@@&'
            ],
            'from decimal (base10) to hexadecimal (base16) - small number' => [
                '9', '0123456789', '0123456789ABCDEF',
                '9'
            ],
            'from decimal (base10) to hexadecimal (base16) - zero' => [
                '0', '0123456789', '0123456789ABCDEF',
                '0'
            ],
            'from decimal (base10) to hexadecimal (base16) - empty' => [
                '', '0123456789', '0123456789ABCDEF',
                ''
            ],
            'from actual binary to decimal' => [
                hex2bin('06169394caed4978a93008ba337bd4df'), null, '0123456789',
                '08092591808379884713638960484539159775'
            ],
            'from decimal (base10) to hexadecimal (base16) - long number, leading zero hex' => [
                '8092591808379884713638960484539159775', '0123456789', '0123456789abcdef',
                '6169394caed4978a93008ba337bd4df'
            ],
            'from full uuid (hex base16, -) to decimal' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', '0123456789',
                '184822225897852159683113138063168215774300634'
            ],
            'from decimal to full uuid (hex base16, -)' => [
                '184822225897852159683113138063168215774300634', '0123456789', '0123456789abcdef-',
                'ff15e4f1-37e0-4fb7-a840-eca043107a31'
            ],
            'from decimal to full uuid (hex base16, -) to short id' => [
                '184822225897852159683113138063168215774300634', '0123456789', '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'REAnAGC8yCk4DF9zH42AmYz8C'
            ],
            'from full uuid (hex base16, -) to short id' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'REAnAGC8yCk4DF9zH42AmYz8C'
            ],
            'from stripped uuid (hex base16) to short id' => [
                'ff15e4f137e04fb7a840eca043107a31', '0123456789abcdef', '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'AvK3WWtNWSQS3LqolQNw9W'
            ],
            'from decimal to full uuid (hex base16, -) to short id (ASCII without zero)' => [
                '184822225897852159683113138063168215774300634', '0123456789', '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'RFBnBHD9yDk5EGAzI53BmYz9D'
            ],
            'from decimal to full uuid (hex base16, -) to short id (ASCII without zero nor O)' => [
                '184822225897852159683113138063168215774300634', '0123456789', '123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'f1KGlCuul4Djx2dFfeJjah2IF'
            ],
            'from full uuid (hex base16, -) to short id (ASCII without zero nor O)' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', '123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'f1KGlCuul4Djx2dFfeJjah2IF'
            ],
            'from stripped uuid (hex base16) to short id (ASCII without zero)' => [
                'ff15e4f137e04fb7a840eca043107a31', '0123456789abcdef', '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'BvL4WWtOWSQS4MqolQOwAW'
            ],
            'from full uuid (hex base16, -) to short id (uppercase ASCII without zero nor O)' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ',
                'QAPVQV4ANTVUMHKK4RUTRB9TNCKJJ'
            ],
            'from stripped uuid (hex base16) to short id (uppercase ASCII without zero nor O)' => [
                'ff15e4f137e04fb7a840eca043107a31', '0123456789abcdef', '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ',
                '2RJAGM4BJGZ1SHLPNQGV74PD9A'
            ],
            'from stripped uuid (hex base16) to short id with (uppercase ASCII without zero nor O)' => [
                'ff15e4f137e04fb7a840eca043107a31', '0123456789abcdef', '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                'F3NS02BLCC33XHV6JZVTVNH9T'
            ],
            'from stripped uuid uppercase (hex base16) to short id (uppercase ASCII without zero)' => [
                'FF15E4F137E04FB7A840ECA043107A31', '0123456789ABCDEF', '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ',
                '2RJAGM4BJGZ1SHLPNQGV74PD9A'
            ],
            'from stripped uuid (hex base16) to short id (uppercase ASCII without zero)' => [
                'ff15e4f137e04fb7a840eca043107a31', '0123456789abcdef', '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                'UP9RYGX74K8TOBBM7R1JV82DO'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidCases
     * @param string $value
     * @param string|null $fromBase
     * @param string|null $toBase
     * @param string $expectedResult
     */
    public function encode_valid(string $value, ?string $fromBase, ?string $toBase, string $expectedResult)
    {
        $classUnderTest = new BaseConv($fromBase, $toBase);
        $result = $classUnderTest->encode($value);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @test
     * @dataProvider provideValidCases
     * @param string $value
     * @param string|null $fromBase
     * @param string|null $toBase
     * @param string $expectedResult
     */
    public function decode_valid(string $expectedResult, ?string $fromBase, ?string $toBase, string $value)
    {
        $classUnderTest = new BaseConv($fromBase, $toBase);
        $result = $classUnderTest->decode($value);
        $this->assertSame($expectedResult, $result);
    }
}
