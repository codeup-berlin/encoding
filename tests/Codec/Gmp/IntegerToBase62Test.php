<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Gmp;

use PHPUnit\Framework\TestCase;

class IntegerToBase62Test extends TestCase
{
    public static function provideDjangoTestCases(): array
    {
        // py: b62_encode(int.from_bytes("".encode()))
        return [
            'zero' => [0, '+0'],
            'positive int 1' => [1, '+1'],
            'negative int -1' => [-1, '-1'],
            'positive int 10' => [10, '+A'],
            'positive int 32432' => [32432, '+8R6'],
            'max int ' . PHP_INT_MAX => [PHP_INT_MAX, '+AzL8n0Y58m7'],
            'max int ' . PHP_INT_MIN => [PHP_INT_MIN, '-AzL8n0Y58m8'],
        ];
    }

    /**
     * @test
     * @dataProvider provideDjangoTestCases
     * @param int $plain
     * @param string $expected
     */
    public function encode(int $plain, string $expected)
    {
        // prepare
        $classUnderTest = new IntegerToBase62();

        // test
        $result = $classUnderTest->encode((string)$plain);

        // verify
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider provideDjangoTestCases
     * @param int $expected
     * @param string $encoded
     */
    public function decode(int $expected, string $encoded)
    {
        // prepare
        $classUnderTest = new IntegerToBase62();

        // test
        $result = (int)$classUnderTest->decode($encoded);

        // verify
        $this->assertSame($expected, $result);
    }
}
