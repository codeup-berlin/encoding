<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Base62;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class HexToBase62Test extends TestCase
{
    /**
     * @test
     */
    public function encode_valid()
    {
        // prepare
        $plain = uniqid('amazing example');
        $base62 = Base62::getEncoder()->encode($plain);
        $hex = bin2hex($plain);
        $classUnderTest = new HexToBase62();

        // test
        $result = $classUnderTest->encode($hex);

        // verify
        $this->assertSame($base62, $result);
    }

    /**
     * @test
     */
    public function encode_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new HexToBase62();
        $classUnderTest->encode('amazing example');
    }
}
