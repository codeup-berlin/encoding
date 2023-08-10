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
    public static function provideUuids(): array
    {
        return [
            'lower case' => [
                '364f1e0d-a2ca-4e83-8139-26b058df27fe',
                '1eTk1odgiy5V3cQwAfUZhe',
                '364f1e0d-a2ca-4e83-8139-26b058df27fe',
            ],
            'upper case' => [
                '364F1E0D-A2CA-4E83-8139-26B058DF27FE',
                '1eTk1odgiy5V3cQwAfUZhe',
                '364f1e0d-a2ca-4e83-8139-26b058df27fe',
            ],
            'leading zero' => [
                '004f1e0d-a2ca-4e83-8139-26b058df27f8',
                'a9iY6w2NfRcMjuZr2Yjg',
                '004f1e0d-a2ca-4e83-8139-26b058df27f8',
            ],
            'tailing zero' => [
                'f04f1e0d-a2ca-4e83-8139-26b058df2700',
                '7JSJXjZOQKCa12w1f1iPBY',
                'f04f1e0d-a2ca-4e83-8139-26b058df2700',
            ],
            'nil' => [
                '00000000-0000-0000-0000-000000000000',
                '0',
                '00000000-0000-0000-0000-000000000000',
            ],
            'min' => [
                '10000000-0000-0000-0000-000000000000',
                'UBsO4td5jEbl6wfnwZr6G',
                '10000000-0000-0000-0000-000000000000',
            ],
            'max' => [
                'ffffffff-ffff-ffff-ffff-ffffffffffff',
                '7n42DGM5Tflk9n8mt7Fhc7',
                'ffffffff-ffff-ffff-ffff-ffffffffffff',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideUuids
     */
    public function encodeDecode_uuid(string $uuid, string $expectedEncoded, string $expectedDecoded)
    {
        $classUnderTest = new UuidToBase62();
        $encoded = $classUnderTest->encode($uuid);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame($expectedEncoded, $encoded);
        $this->assertSame(mb_strtolower($uuid), mb_strtolower($decoded));
        $this->assertSame($expectedDecoded, $decoded);
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
