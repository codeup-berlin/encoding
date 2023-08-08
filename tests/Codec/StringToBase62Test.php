<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use PHPUnit\Framework\TestCase;

class StringToBase62Test extends TestCase
{
    public static function provideDjangoTestCases(): array
    {
        // py: b62_encode(int.from_bytes("".encode()))
        return [
            'empty string' => ['', ''],
            'zero' => ['0', 'm'],
            'one' => ['1', 'n'],
            'float 0.1' => ['0.1', 'DFQH'],
            'negative int -1' => ['-1', '30b'],
            'pure string "bla"' => ['bla', 'R40f'],
            'max int ' . PHP_INT_MAX => ['9223372036854775807', '1yXbZds9dSgxwaidhXBYHcjwMZ'],
            'uuid' => ['1179c90c-e3d2-4ce7-8870-3df743add313', 'sgbm4lr7h711pKynhiNBcGflkoGyCWZyBOvORJFuA8gdp2El'],
            'uuid nil' => ['00000000-0000-0000-0000-000000000000', 'rZPELhMUZzQ6153VPkcPQAzJsDKO9ygnfeDZBh4Cffxj9amO'],
            'integer with leading zero' => ['0000815', '107Zzj7rPR'],
        ];
    }

    /**
     * @test
     * @dataProvider provideDjangoTestCases
     * @param string $plain
     * @param string $expected
     */
    public function encode(string $plain, string $expected)
    {
        // prepare
        $classUnderTest = new StringToBase62();

        // test
        $result = $classUnderTest->encode($plain);

        // verify
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider provideDjangoTestCases
     * @param string $expected
     * @param string $encoded
     */
    public function decode(string $expected, string $encoded)
    {
        // prepare
        $classUnderTest = new StringToBase62();

        // test
        $result = $classUnderTest->decode($encoded);

        // verify
        $this->assertSame($expected, $result);
    }
}
