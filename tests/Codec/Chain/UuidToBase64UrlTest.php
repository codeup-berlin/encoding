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
     * @return array
     */
    public static function provideUuids(): array
    {
        return [
            'lower case' => [
                '364f1e0d-a2ca-4e83-8139-26b058df27fe',
                'Nk8eDaLKToOBOSawWN8n_g',
                '364f1e0d-a2ca-4e83-8139-26b058df27fe',
            ],
            'upper case' => [
                '364F1E0D-A2CA-4E83-8139-26B058DF27FE',
                'Nk8eDaLKToOBOSawWN8n_g',
                '364f1e0d-a2ca-4e83-8139-26b058df27fe',
            ],
            'leading zero' => [
                '004f1e0d-a2ca-4e83-8139-26b058df27f8',
                'AE8eDaLKToOBOSawWN8n-A',
                '004f1e0d-a2ca-4e83-8139-26b058df27f8',
            ],
            'tailing zero' => [
                'f04f1e0d-a2ca-4e83-8139-26b058df2700',
                '8E8eDaLKToOBOSawWN8nAA',
                'f04f1e0d-a2ca-4e83-8139-26b058df2700',
            ],
            'nil' => [
                '00000000-0000-0000-0000-000000000000',
                'AAAAAAAAAAAAAAAAAAAAAA',
                '00000000-0000-0000-0000-000000000000',
            ],
            'min' => [
                '10000000-0000-0000-0000-000000000000',
                'EAAAAAAAAAAAAAAAAAAAAA',
                '10000000-0000-0000-0000-000000000000',
            ],
            'max' => [
                'ffffffff-ffff-ffff-ffff-ffffffffffff',
                '_____________________w',
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
        $classUnderTest = new UuidToBase64Url();
        $encoded = $classUnderTest->encode($uuid);
        $decoded = $classUnderTest->decode($encoded);
        $this->assertSame($expectedEncoded, $encoded);
        $this->assertSame(mb_strtolower($uuid), mb_strtolower($decoded));
        $this->assertSame($expectedDecoded, $decoded);
    }
}
