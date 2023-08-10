<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Gmp;

use Codeup\Encoding\Base62;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class HexToBase62Test extends TestCase
{
    /**
     * @test
     */
    public function encode_hex()
    {
        // prepare
        $bin = uniqid('amazing example');
        $base62 = Base62::getEncoder()->encode($bin);
        $hex = bin2hex($bin);
        $classUnderTest = new HexToBase62();

        // test
        $result = $classUnderTest->encode($hex);

        // verify
        $this->assertSame($base62, $result);
    }

    /**
     * @test
     */
    public function encode_nonHex()
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new HexToBase62();
        $classUnderTest->encode('this contains non-hex characters');
    }

    /**
     * @test
     */
    public function decode_base62encodedHex()
    {
        // prepare
        $bin = uniqid('amazing example');
        $base62 = Base62::getEncoder()->encode($bin);
        $hex = bin2hex($bin);
        $classUnderTest = new HexToBase62();

        // test
        $result = $classUnderTest->decode($base62);

        // verify
        $this->assertSame($hex, $result);
    }

    /**
     * @test
     */
    public function decode_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new HexToBase62();
        $classUnderTest->decode('this contains non-base62 characters');
    }

    /**
     * @return array[]
     */
    public static function provideValues(): array
    {
        return [
            'even leading zero sha256' => [
                '0000a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
                'a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
            ],
            'odd leading zero sha256' => [
                '0007a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
                '7a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValues
     * @param string $value
     * @param string $expected
     */
    public function encodeDecode_sha256(string $value, string $expected)
    {
        $classUnderTest = new HexToBase62();
        $encoded = $classUnderTest->encode($value);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame($expected, $decoded);
    }
}
