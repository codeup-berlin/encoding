<?php
namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;
use InvalidArgumentException;

class Hex implements EncodingStrategy
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        return bin2hex($data);
    }

    /**
     * @param string $data
     * @return string
     * @throws \InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        $result = hex2bin($data);
        if (false === $result) {
            throw new InvalidArgumentException('Invalid hex string.');
        }
        return $result;
    }
}
