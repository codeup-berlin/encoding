<?php

declare(strict_types=1);

namespace Codeup\Encoding;

interface Decompactor
{
    /**
     * @param int|string $value
     * @return int|string
     */
    public function decompact(int|string $value): int|string;
}
