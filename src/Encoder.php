<?php
namespace Codeup\Encoding;

interface Encoder
{
    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string;
}
