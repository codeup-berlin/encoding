<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec;
use InvalidArgumentException;
use SodiumException;

class BinToBase64Url implements Codec
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
            throw new InvalidArgumentException("BinToBase64Url encoding failed: {$e->getMessage()}", 0, $e);
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
            throw new InvalidArgumentException("BinToBase64Url encoding failed: {$e->getMessage()}", 0, $e);
        }
    }
}
