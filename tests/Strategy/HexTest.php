<?php

namespace Codeup\Encoding\Strategy;

class HexTest extends \PHPUnit\Framework\TestCase
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
     * @expectedException \InvalidArgumentException
     */
    public function decode_invalidString()
    {
        $classUnderTest = new Hex();
        @$classUnderTest->decode('626c6');
    }
}
