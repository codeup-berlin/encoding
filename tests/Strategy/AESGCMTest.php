<?php
namespace Codeup\Encoding\Strategy;

class AESGCMTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \InvalidArgumentException
     */
    public function integration_decryptInvalidData()
    {
        // prepare
        $classUnderTest = new AESGCM(uniqid('key'), uniqid('iv'));

        // test
        $classUnderTest->decode(uniqid('something'));

        // verified by mock
    }
}
