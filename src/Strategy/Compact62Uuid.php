<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;

class Compact62Uuid extends Chain
{
    const BASE_HEX = '0123456789abcdef';
    const BASE_62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @param StripUuid $stripUuid
     * @param Base64Url $base62Url
     */
    public function __construct(StripUuid $stripUuid, EncodingStrategy $base62Url)
    {
        $this->append($stripUuid);
        $this->append($base62Url);
    }

    /**
     * @return static
     */
    public static function makeDefault(): static
    {
        return new self(new StripUuid(), new BaseConv(BaseConv::BASE_HEX, self::BASE_62));
    }
}
