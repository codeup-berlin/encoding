<?php
namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;
use InvalidArgumentException;

class StripUuid implements EncodingStrategy
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        if (!preg_match('/^[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}$/', $data)) {
            throw new InvalidArgumentException('Not a valid uuid: ' . $data);
        }
        return str_replace('-', '', $data);
    }

    /**
     * @param string $data
     * @return string
     * @throws \InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        if (!preg_match('/^[a-f0-9]{32}$/', $data)) {
            throw new InvalidArgumentException('Not a stripped uuid: ' . $data);
        }
        return implode('-', [
            substr($data, 0, 8),
            substr($data, 8, 4),
            substr($data, 12, 4),
            substr($data, 16, 4),
            substr($data, 20),
        ]);
    }
}
