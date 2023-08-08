<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Chain;

use Codeup\Encoding\Codec\BinToBase64Url;
use Codeup\Encoding\Codec\BinToHex;
use Codeup\Encoding\Codec\Chain;
use Codeup\Encoding\Codec\StripUuid;

class UuidToBase64Url extends Chain
{
    public function __construct()
    {
        $this->append(new StripUuid());
        $this->appendInverted(new BinToHex());
        $this->append(new BinToBase64Url());
    }
}
