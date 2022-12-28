<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;
use InvalidArgumentException;
use RuntimeException;
use SodiumException;

class Base64Url implements EncodingStrategy
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        try {
            return sodium_bin2base64($data, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
        } catch (SodiumException $e) {
            throw new RuntimeException("Base64Url encoding failed: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        try {
            return sodium_base642bin($data, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
        } catch (SodiumException $e) {
            throw new InvalidArgumentException("Base64Url encoding failed: {$e->getMessage()}", 0, $e);
        }
    }
}
