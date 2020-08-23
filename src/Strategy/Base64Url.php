<?php
namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;

class Base64Url implements EncodingStrategy
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    /**
     * @param string $data
     * @return string
     * @throws \InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        if ($lastTokenLength = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $lastTokenLength);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
