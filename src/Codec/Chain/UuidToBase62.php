<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Chain;

use Codeup\Encoding\Codec\Chain;
use Codeup\Encoding\Codec\Gmp\HexToBase62;
use Codeup\Encoding\Codec\StripUuid;

class UuidToBase62 extends Chain
{
    public function __construct()
    {
        $this->append(new StripUuid());
        $this->append(new HexToBase62());
    }
}
