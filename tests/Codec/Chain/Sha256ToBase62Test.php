<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Chain;

use PHPUnit\Framework\TestCase;

class Sha256ToBase62Test extends TestCase
{
    /**
     * @return array[]
     */
    public static function provideValues(): array
    {
        return [
//            'simple' => [
//                '76f5a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//                '76f5a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//            ],
//            'leading even zeros' => [
//                '0000a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//                '0000a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//            ],
//            'leading odd zeros' => [
//                '0007a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//                '0007a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//            ],
            'upper case' => [
                '76F5A456E7B5A5EB059E721FB431436883143101275C4077F83FE70298F5623D',
                '76f5a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
            ],
//            'too short' => [
//                'a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//                '0000a456e7b5a5eb059e721fb431436883143101275c4077f83fe70298f5623d',
//            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValues
     * @param string $value
     * @param string $expectedDecoded
     */
    public function encodeDecode_valid(string $value, string $expectedDecoded)
    {
        // prepare
        $classUnderTest = new Sha256ToBase62();

        // test
        $encoded = $classUnderTest->encode($value);
        $decoded = $classUnderTest->decode($encoded);

        // verify
        $this->assertSame($expectedDecoded, $decoded);
    }
}
