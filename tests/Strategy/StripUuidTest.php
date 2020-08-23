<?php

namespace Codeup\Encoding\Strategy;

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
    public function provideNonUuidValues()
    {
        return [
            'missing char' => ['364f1e0d-a2ca-4e83-8139-26b058df27f'],
            'stripped uuid' => ['364f1e0da2ca4e83813926b058df27fe'],
            'invalid char' => ['364f1e0d-a2ca-4e83-8139-26b058df27g'],
            'number' => [234],
            'random' => [uniqid()],
        ];
    }

    /**
     * @test
     * @dataProvider provideNonUuidValues
     * @param $value
     */
    public function encode_nonUuid($value)
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new StripUuid();
        $classUnderTest->encode($value);
    }

    /**
     * @test
     */
    public function decode_strippedUuid()
    {
        $classUnderTest = new StripUuid();
        $result = $classUnderTest->decode('364f1e0da2ca4e83813926b058df27fe');
        $this->assertSame('364f1e0d-a2ca-4e83-8139-26b058df27fe', $result);
    }

    /**
     * @return array
     */
    public function provideNonStrippedUuidValues()
    {
        return [
            'missing char' => ['364f1e0d-a2ca-4e83-8139-26b058df27f'],
            'unstripped uuid' => ['364f1e0d-a2ca-4e83-8139-26b058df27fe'],
            'invalid char' => ['364f1e0da2ca4e83813926b058df27g'],
            'number' => [234],
            'random' => [uniqid()],
        ];
    }

    /**
     * @test
     * @dataProvider provideNonStrippedUuidValues
     * @param $value
     */
    public function decode_nonUuid($value)
    {
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new StripUuid();
        $classUnderTest->decode($value);
    }
}
