<?php

namespace Codeup\Encoding\Strategy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class HexTest extends TestCase
{
    /**
     * @test
     */
    public function encode_validString()
    {
        $classUnderTest = new Hex();
        $result = $classUnderTest->encode('bla');
        $this->assertSame('626c61', $result);
    }

    /**
     * @test
     */
    public function decode_validString()
    {
        $classUnderTest = new Hex();
        $result = $classUnderTest->decode('626c61');
        $this->assertSame('bla', $result);
    }

    /**
     * @test
     */
    public function decode_invalidString()
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new Hex();
        @$classUnderTest->decode('626c6');
    }
}
