<?php

declare(strict_types=1);

namespace Codeup\Encoding\Strategy;

class Compact64Uuid extends Chain
{
    /**
     * @param StripUuid $stripUuid
     * @param Hex $hex
     * @param Base64Url $base64Url
     */
    public function __construct(StripUuid $stripUuid, Hex $hex, Base64Url $base64Url)
    {
        $this->append($stripUuid);
        $this->appendInverted($hex);
        $this->append($base64Url);
    }

    /**
     * @return Compact64Uuid
     */
    public static function makeDefault(): Compact64Uuid
    {
        return new self(new StripUuid(), new Hex(), new Base64Url());
    }
}
