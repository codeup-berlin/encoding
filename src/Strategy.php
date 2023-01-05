<?php

declare(strict_types=1);

namespace Codeup\Encoding;

interface Strategy extends Encoder, Decoder
{
    public const BASE_DEC = '0123456789';
    public const BASE_HEX = '0123456789abcdef';
    public const BASE_62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
}
