<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StripUuidTest extends TestCase
{
    /**
     * @test
     */
    public function encode_uuid()
    {
        $classUnderTest = new StripUuid();
        $result = $classUnderTest->encode('364f1e0d-a2ca-4e83-8139-26b058df27fe');
        $this->assertSame('364f1e0da2ca4e83813926b058df27fe', $result);
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
        $classUnderTest = new StripUuid();
        $classUnderTest->encode($value);
    }

    /**
     * @test
     */
    public function decode_uuid()
    {
        $classUnderTest = new StripUuid();
        $result = $classUnderTest->decode('364f1e0da2ca4e83813926b058df27fe');
        $this->assertSame('364f1e0d-a2ca-4e83-8139-26b058df27fe', $result);
    }

    /**
     * @return array
     */
    public static function provideStrippedUuidValues(): array
    {
        return [
            'number 234' => ['234'],
            'any int' => [(string)rand()],
            'any hex int' => [bin2hex((string)rand())],
            'max int ' . PHP_INT_MAX => [(string)PHP_INT_MAX],
            'random' => [uniqid()],
        ];
    }

    /**
     * @test
     * @dataProvider provideStrippedUuidValues
     */
    public function decode_strippedUuid(string $value)
    {
        $classUnderTest = new StripUuid();
        $result = $classUnderTest->decode($value);
        $this->assertNotFalse(preg_match('/^[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}$/', $result));
    }

    /**
     * @return array
     */
    public static function provideNonStrippedUuidValues(): array
    {
        return [
            'missing char' => ['364f1e0d-a2ca-4e83-8139-26b058df27f'],
            'non-stripped uuid' => ['364f1e0d-a2ca-4e83-8139-26b058df27fe'],
            'invalid char' => ['364f1e0da2ca4e83813926b058df27g'],
        ];
    }

    /**
     * @test
     * @dataProvider provideNonStrippedUuidValues
     * @param string $value
     */
    public function decode_nonStrippedUuid(string $value)
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new StripUuid();
        $classUnderTest->decode($value);
    }
}
