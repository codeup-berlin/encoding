<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Alphabet;
use PHPUnit\Framework\TestCase;

class BaseConvTest extends TestCase
{
    /**
     * @return array
     */
    public static function provideValidCases(): array
    {
        return [
            'from binary to binary' => [
                hex2bin('580a7aa5d6610ac3'), Alphabet::BINARY, Alphabet::BINARY,
                hex2bin('580a7aa5d6610ac3'),
            ],
            'from binary to hexadecimal (base16)' => [
                hex2bin('580a7aa5d6610ac3'), Alphabet::BINARY, Alphabet::BASE16_LOWER,
                '580a7aa5d6610ac3',
            ],
            'from binary to password alphabet' => [
                hex2bin('580a7aa5d6610ac3'), Alphabet::BINARY, '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!^"§$%&/()=?*\'_:;,.-#+',
                'VeUqZ;+a.J',
            ],
            'from decimal (base10) to octal (base8)' => [
                '123', Alphabet::DECIMAL, '01234567',
                '173',
            ],
            'from hexadecimal (base16) to base2' => [
                '70B1D707EAC2EDF4C6389F440C7294B51FFF57BB', Alphabet::BASE16_UPPER, '01',
                '111000010110001110101110000011111101010110000101110110111110100110001100011100010011111010001000000110001110010100101001011010100011111111111110101011110111011',
            ],
            'from senary (base6) to hexadecimal (base16)' => [
                '1324523453243154324542341524315432113200203012', '012345', Alphabet::HEX,
                '1f9881bad10454a8c23a838ef00f50',
            ],
            'from decimal (base10) to undecimal (base11) using "Christo" as the numbers' => [
                '355927353784509896715106760', Alphabet::DECIMAL, 'Christo',
                'rhtitiCtrohhisshhCssCotthitsoitC',
            ],
            'from octodecimal (base18) using "0123456789aAbBcCdD" as the numbers to undecimal (base11) using "~!@#$%^&*()" as the numbers' => [
                '1C238Ab97132aAC84B72', '0123456789aAbBcCdD', '~!@#$%^&*()',
                '!%~!!*&!~^!!&(&!~^@#@@@&',
            ],
            'from decimal (base10) to hexadecimal (base16) - small number' => [
                '9', Alphabet::DECIMAL, Alphabet::HEX,
                '9',
            ],
            'from decimal (base10) to hexadecimal (base16) - zero' => [
                '0', Alphabet::DECIMAL, Alphabet::BASE16_UPPER,
                '0',
            ],
            'from decimal (base10) to hexadecimal (base16) - empty' => [
                '', Alphabet::DECIMAL, Alphabet::BASE16_UPPER,
                '',
            ],
            'from actual binary to decimal' => [
                hex2bin('06169394caed4978a93008ba337bd4df'), Alphabet::BINARY, Alphabet::DECIMAL,
                '08092591808379884713638960484539159775',
            ],
            'from decimal (base10) to hexadecimal (base16) - long number, leading zero hex' => [
                '8092591808379884713638960484539159775', Alphabet::DECIMAL, Alphabet::HEX,
                '6169394caed4978a93008ba337bd4df',
            ],
            'from full uuid (hex base16, -) to decimal' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', Alphabet::DECIMAL,
                '184822225897852159683113138063168215774300634',
            ],
            'from decimal to full uuid (hex base16, -)' => [
                '184822225897852159683113138063168215774300634', Alphabet::DECIMAL, '0123456789abcdef-',
                'ff15e4f1-37e0-4fb7-a840-eca043107a31',
            ],
            'from decimal to full uuid (hex base16, -) to short id' => [
                '184822225897852159683113138063168215774300634', Alphabet::DECIMAL, '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'REAnAGC8yCk4DF9zH42AmYz8C',
            ],
            'from full uuid (hex base16, -) to short id' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'REAnAGC8yCk4DF9zH42AmYz8C',
            ],
            'from stripped uuid (hex base16) to short id' => [
                'ff15e4f137e04fb7a840eca043107a31', Alphabet::HEX, '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'AvK3WWtNWSQS3LqolQNw9W',
            ],
            'from decimal to full uuid (hex base16, -) to short id (ASCII without zero)' => [
                '184822225897852159683113138063168215774300634', Alphabet::DECIMAL, '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'RFBnBHD9yDk5EGAzI53BmYz9D',
            ],
            'from decimal to full uuid (hex base16, -) to short id (ASCII without zero nor O)' => [
                '184822225897852159683113138063168215774300634', Alphabet::DECIMAL, '123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'f1KGlCuul4Djx2dFfeJjah2IF',
            ],
            'from full uuid (hex base16, -) to short id (ASCII without zero nor O)' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', '123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'f1KGlCuul4Djx2dFfeJjah2IF',
            ],
            'from stripped uuid (hex base16) to short id (ASCII without zero)' => [
                'ff15e4f137e04fb7a840eca043107a31', Alphabet::HEX, '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                'BvL4WWtOWSQS4MqolQOwAW',
            ],
            'from full uuid (hex base16, -) to short id (uppercase ASCII without zero nor O)' => [
                'ff15e4f1-37e0-4fb7-a840-eca043107a31', '0123456789abcdef-', '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ',
                'QAPVQV4ANTVUMHKK4RUTRB9TNCKJJ',
            ],
            'from stripped uuid (hex base16) to short id (uppercase ASCII without zero nor O)' => [
                'ff15e4f137e04fb7a840eca043107a31', Alphabet::HEX, '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ',
                '2RJAGM4BJGZ1SHLPNQGV74PD9A',
            ],
            'from stripped uuid (hex base16) to short id with (uppercase ASCII without zero nor O)' => [
                'ff15e4f137e04fb7a840eca043107a31', Alphabet::HEX, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                'F3NS02BLCC33XHV6JZVTVNH9T',
            ],
            'from stripped uuid uppercase (hex base16) to short id (uppercase ASCII without zero)' => [
                'FF15E4F137E04FB7A840ECA043107A31', Alphabet::BASE16_UPPER, '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ',
                '2RJAGM4BJGZ1SHLPNQGV74PD9A',
            ],
            'from stripped uuid (hex base16) to short id (uppercase ASCII without zero)' => [
                'ff15e4f137e04fb7a840eca043107a31', Alphabet::HEX, '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                'UP9RYGX74K8TOBBM7R1JV82DO',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidCases
     * @param string $value
     * @param Alphabet|string $fromAlphabet
     * @param Alphabet|string $toAlphabet
     * @param string $expectedResult
     */
    public function encode_valid(
        string $value,
        Alphabet|string $fromAlphabet,
        Alphabet|string $toAlphabet,
        string $expectedResult,
    ) {
        $classUnderTest = new BaseConv($this->alphabetAsString($fromAlphabet), $this->alphabetAsString($toAlphabet));
        $result = $classUnderTest->encode($value);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @test
     * @dataProvider provideValidCases
     * @param string $expectedResult
     * @param Alphabet|string $fromAlphabet
     * @param Alphabet|string $toAlphabet
     * @param string $value
     */
    public function decode_valid(
        string $expectedResult,
        Alphabet|string $fromAlphabet,
        Alphabet|string $toAlphabet,
        string $value,
    ) {
        $classUnderTest = new BaseConv($this->alphabetAsString($fromAlphabet), $this->alphabetAsString($toAlphabet));
        $result = $classUnderTest->decode($value);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @param Alphabet|string $fromAlphabet
     * @return string
     */
    private function alphabetAsString(Alphabet|string $fromAlphabet): string
    {
        return is_string($fromAlphabet) ? $fromAlphabet : $fromAlphabet->value;
    }
}
