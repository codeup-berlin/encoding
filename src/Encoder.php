<?php
namespace Codeup\Encoding;

interface Encoder
{
    /**
     * @param string $data
     * @return string
     * @throws \InvalidArgumentException if the passed data can not be decoded
     */
    public function encode(string $data): string;
}
