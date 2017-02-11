<?php
namespace Codeup\Encoding;

interface Decoder
{
    /**
     * @param string $data
     * @return string
     * @throws \InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string;
}
