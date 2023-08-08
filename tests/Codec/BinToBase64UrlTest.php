<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use PHPUnit\Framework\TestCase;

class BinToBase64UrlTest extends TestCase
{
    /**
     * @test
     */
    public function encode_withSpecialChars()
    {
        // prepare
        $base64encodedData = 'd+W/tA==';
        $base64UrlDecodedData = base64_decode($base64encodedData);
        $expectedData = 'd-W_tA';
        $classUnderTest = new BinToBase64Url();

        // test
        $result = $classUnderTest->encode($base64UrlDecodedData);

        // verify
        $this->assertSame($expectedData, $result);
    }

    /**
     * @test
     */
    public function decode_withSpecialChars()
    {
        // prepare
        $base64encodedData = 'd+W/tA==';
        $base64UrlEncodedData = 'd-W_tA';
        $expectedData = base64_decode($base64encodedData);
        $classUnderTest = new BinToBase64Url();

        // test
        $result = $classUnderTest->decode($base64UrlEncodedData);

        // verify
        $this->assertSame($expectedData, $result);
    }
}
