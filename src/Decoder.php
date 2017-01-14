<?php
namespace Codeup\Encoding;

interface Decoder
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string;
}
