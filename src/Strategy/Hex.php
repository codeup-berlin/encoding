<?php
namespace Codeup\Encoding\Strategy;

class Hex implements \Codeup\Encoding\Strategy
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
            throw new \InvalidArgumentException('Invalid hex string.');
        }
        return $result;
    }
}
