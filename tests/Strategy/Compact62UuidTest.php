<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class Compact62UuidTest extends TestCase
{
    /**
     * @return array
     */
    public function provideValidRoundtripCases(): array
    {
        return [
            'uuid' => ['364f1e0d-a2ca-4e83-8139-26b058df27fe'],
            'uuid leading zero' => ['064f1e0d-a2ca-4e83-8139-26b058df27fe'],
            'uuid nil' => ['00000000-0000-0000-0000-000000000000'],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidRoundtripCases
     */
    public function decodeEncode_edgeCase(string $value)
    {
        $classUnderTest = Compact62Uuid::makeDefault();

        $encoded = $classUnderTest->encode($value);
        $decoded = $classUnderTest->decode($encoded);

        $this->assertSame($value, $decoded);
    }

    /**
     * @return array
     */
    public function provideNonUuidValues(): array
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
        $classUnderTest = Compact62Uuid::makeDefault();
        $classUnderTest->encode($value);
    }

    /**
     * @test
     */
    public function decode_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = Compact62Uuid::makeDefault();
        $classUnderTest->decode(uniqid('something weired'));
    }
}
