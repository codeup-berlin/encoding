<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec;
use InvalidArgumentException;

class StripUuid implements Codec
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
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        $data = str_pad($data, 32, '0', STR_PAD_LEFT);
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
