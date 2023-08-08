<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec;
use Exception;
use InvalidArgumentException;

class AESGCM implements Codec
{
    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private string $iv;

    /**
     * @param string $key
     * @param string $iv
     */
    public function __construct(string $key, string $iv)
    {
        $this->key = $key;
        $this->iv = $iv;
    }

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        return \AESGCM\AESGCM::encryptAndAppendTag($this->key, $this->iv, $data, '');
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException
     */
    public function decode(string $data): string
    {
        try {
            return \AESGCM\AESGCM::decryptWithAppendedTag($this->key, $this->iv, $data, '');
        } catch (Exception $e) {
            throw new InvalidArgumentException('Decryption failed.', 0, $e);
        }
    }
}
