<?php
namespace Codeup\Encoding\Strategy;

use PHPUnit\Framework\TestCase;

class Base64UrlTest extends TestCase
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
        $classUnderTest = new Base64Url();

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
        $classUnderTest = new Base64Url();

        // test
        $result = $classUnderTest->decode($base64UrlEncodedData);

        // verify
        $this->assertSame($expectedData, $result);
    }
}
