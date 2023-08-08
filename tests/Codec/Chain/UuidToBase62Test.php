<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Chain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UuidToBase62Test extends TestCase
{
    /**
     * @return array
     */
    public static function provideValidRoundtripCases(): array
    {
        return [
            'uuid' => ['364f1e0d-a2ca-4e83-8139-26b058df27fe'],
            'uuid leading zero' => ['064f1e0d-a2ca-4e83-8139-26b058df27fe'],
            'uuid nil' => ['00000000-0000-0000-0000-000000000000'],
            'uuid min' => ['10000000-0000-0000-0000-000000000000'],
            'uuid max' => ['ffffffff-ffff-ffff-ffff-ffffffffffff'],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidRoundtripCases
     */
    public function roundtrip_edgeCase(string $value)
    {
        $classUnderTest = new UuidToBase62();

        $encoded = $classUnderTest->encode($value);
        $decoded = $classUnderTest->decode($encoded);

        $this->assertSame($value, $decoded);
    }

    /**
     * @return array
     */
    public static function provideNonUuidValues(): array
    {
        return [
            'missing char' => ['364f1e0d-a2ca-4e83-8139-26b058df27f'],
            'stripped uuid' => ['364f1e0da2ca4e83813926b058df27fe'],
            'invalid char' => ['364f1e0d-a2ca-4e83-8139-26b058df27g'],
            'number' => ['234'],
            'random' => [uniqid()],
            'empty' => [''],
        ];
    }

    /**
     * @test
     * @dataProvider provideNonUuidValues
     * @param string $value
     */
    public function encode_nonUuid(string $value)
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new UuidToBase62();
        $classUnderTest->encode($value);
    }

    /**
     * @return array
     */
    public static function provideNumbers(): array
    {
        return [
            'int 0' => ['0'],
            'int 234' => ['234'],
            'any int' => [(string)rand()],
            'max int ' . PHP_INT_MAX => [(string)PHP_INT_MAX],
        ];
    }

    /**
     * @test
     * @dataProvider provideNumbers
     * @param string $value
     */
    public function decode_numbers(string $value)
    {
        $classUnderTest = new UuidToBase62();
        $result = $classUnderTest->decode($value);
        $this->assertNotFalse(preg_match('/^[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}$/', $result));
    }

    /**
     * @test
     */
    public function decode_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new UuidToBase62();
        $classUnderTest->decode(uniqid('something weired'));
    }
}
