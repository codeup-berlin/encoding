<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AESGCMTest extends TestCase
{
    /**
     * @test
     */
    public function integration_encryptDecrypt()
    {
        // prepare
        $classUnderTest = new AESGCM(uniqid('key'), uniqid('iv'));
        $data = uniqid('data');

        // test
        $encryptedData = $classUnderTest->encode($data);
        $decryptedData = $classUnderTest->decode($encryptedData);

        // verify
        $this->assertSame($data, $decryptedData);
    }
    /**
     * @test
     */
    public function integration_decryptInvalidData()
    {
        // prepare
        $this->expectException(InvalidArgumentException::class);
        $classUnderTest = new AESGCM(uniqid('key'), uniqid('iv'));

        // test
        $classUnderTest->decode(uniqid('something'));

        // verified by mock
    }
}
