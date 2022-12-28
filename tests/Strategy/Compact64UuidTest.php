<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class Compact64UuidTest extends TestCase
{
    /**
     * @test
     */
    public function encode_uuid()
    {
        $classUnderTest = Compact64Uuid::makeDefault();
        $result = $classUnderTest->encode('364f1e0d-a2ca-4e83-8139-26b058df27fe');
        $this->assertSame('Nk8eDaLKToOBOSawWN8n_g', $result);
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
        $classUnderTest = Compact64Uuid::makeDefault();
        $classUnderTest->encode($value);
    }

    /**
     * @test
     */
    public function decode_strippedUuid()
    {
        $classUnderTest = Compact64Uuid::makeDefault();
        $result = $classUnderTest->decode('Nk8eDaLKToOBOSawWN8n_g');
        $this->assertSame('364f1e0d-a2ca-4e83-8139-26b058df27fe', $result);
    }

    /**
     * @return array
     */
    public function provideNonStrippedUuidValues(): array
    {
        return [
            'missing char' => ['364f1e0d-a2ca-4e83-8139-26b058df27f'],
            'unstripped uuid' => ['364f1e0d-a2ca-4e83-8139-26b058df27fe'],
            'invalid char' => ['364f1e0da2ca4e83813926b058df27g'],
            'number' => ['234'],
            'random' => [uniqid()],
        ];
    }

    /**
     * @test
     * @dataProvider provideNonStrippedUuidValues
     * @param string $value
     */
    public function decode_nonUuid(string $value)
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = Compact64Uuid::makeDefault();
        $classUnderTest->decode($value);
    }

    /**
     * @test
     */
    public function encodeDecode_uuidWithLeadingZero()
    {
        $classUnderTest = Compact64Uuid::makeDefault();
        $uuid = '004f1e0d-a2ca-4e83-8139-26b058df27f0';
        $encoded = $classUnderTest->encode($uuid);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame('AE8eDaLKToOBOSawWN8n8A', $encoded);
        $this->assertSame($uuid, $decoded);
    }

    /**
     * @test
     */
    public function encodeDecode_niUuuid()
    {
        $classUnderTest = Compact64Uuid::makeDefault();
        $uuid = '00000000-0000-0000-0000-000000000000';
        $encoded = $classUnderTest->encode($uuid);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame('AAAAAAAAAAAAAAAAAAAAAA', $encoded);
        $this->assertSame($uuid, $decoded);
    }
}
