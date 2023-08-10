<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec\Chain;

use Codeup\Encoding\Codec\Chain;
use Codeup\Encoding\Codec\Gmp\HexToBase62;
use Codeup\Encoding\Codec\ZeroHeaded;

class ZeroHeadedHexToBase62 extends Chain
{
    /**
     * @param int $minimalDecodedLength
     */
    public function __construct(int $minimalDecodedLength)
    {
        $this->append(new ZeroHeaded($minimalDecodedLength));
        $this->append(new HexToBase62());
    }
}
