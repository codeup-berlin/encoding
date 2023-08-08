<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Chain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UuidToBase64UrlTest extends TestCase
{
    /**
     * @test
     */
    public function encode_uuid()
    {
        $classUnderTest = new UuidToBase64Url();
        $result = $classUnderTest->encode('364f1e0d-a2ca-4e83-8139-26b058df27fe');
        $this->assertSame('Nk8eDaLKToOBOSawWN8n_g', $result);
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
        $classUnderTest = new UuidToBase64Url();
        $classUnderTest->encode($value);
    }

    /**
     * @test
     */
    public function decode_uuid()
    {
        $classUnderTest = new UuidToBase64Url();
        $result = $classUnderTest->decode('Nk8eDaLKToOBOSawWN8n_g');
        $this->assertSame('364f1e0d-a2ca-4e83-8139-26b058df27fe', $result);
    }
    /**
     * @param string $value
     * @return string
     */
    private static function base64urlEncode(string $value): string
    {
        return sodium_bin2base64($value, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
    }

    /**
     * @return array
     */
    public static function provideNumbers(): array
    {
        return [
            'int 234' => [self::base64urlEncode('234')],
            'int 1366440293' => [self::base64urlEncode('1366440293')],
            'any int' => [self::base64urlEncode((string)rand(1, 9999999999999999))],
            'max int 9999999999999999' => [self::base64urlEncode('9999999999999999')],
        ];
    }

    /**
     * @test
     * @dataProvider provideNumbers
     * @param string $value
     */
    public function decode_numbers(string $value)
    {
        $classUnderTest = new UuidToBase64Url();
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
        $classUnderTest = new UuidToBase64Url();
        $classUnderTest->decode($value);
    }

    /**
     * @test
     */
    public function roundtrip_uuidWithLeadingZero()
    {
        $classUnderTest = new UuidToBase64Url();
        $uuid = '004f1e0d-a2ca-4e83-8139-26b058df27f0';
        $encoded = $classUnderTest->encode($uuid);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame('AE8eDaLKToOBOSawWN8n8A', $encoded);
        $this->assertSame($uuid, $decoded);
    }

    /**
     * @test
     */
    public function roundtrip_nilUuid()
    {
        $classUnderTest = new UuidToBase64Url();
        $uuid = '00000000-0000-0000-0000-000000000000';
        $encoded = $classUnderTest->encode($uuid);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame('AAAAAAAAAAAAAAAAAAAAAA', $encoded);
        $this->assertSame($uuid, $decoded);
    }
}
