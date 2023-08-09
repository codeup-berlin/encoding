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
}
