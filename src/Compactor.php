<?php

declare(strict_types=1);

namespace Codeup\Encoding;

interface Compactor
{
    /**
     * @param int|string $value
     * @return int|string
     */
    public function compact(int|string $value): int|string;
}
