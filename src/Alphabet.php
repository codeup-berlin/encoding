<?php

/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace Codeup\Encoding;

enum Alphabet: string
{
    case BINARY = '';
    case BASE10 = '0123456789';
    case BASE16_RFC4648 = '0123456789ABCDEF';
    case BASE16_LOWER = '0123456789abcdef';
    case BASE62_GMP = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    case BASE62_BASEX = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    case BASE62_BASE64SUBSET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    const DEC = self::BASE10;
    const DECIMAL = self::DEC;
    const BASE16 = self::BASE16_LOWER;
    const BASE16_UPPER = self::BASE16_RFC4648;
    const HEX = self::BASE16_LOWER;
    const HEXADECIMAL = self::HEX;
    const BASE62 = self::BASE62_GMP;

    /**
     * @param string $value
     * @return bool
     */
    public function isValidString(string $value): bool
    {
        return match($this) {
            self::BINARY => true,
            self::BASE10 => ctype_digit($value),
            self::BASE16_RFC4648 => ctype_xdigit($value) && ctype_upper($value),
            self::BASE16_LOWER => ctype_xdigit($value) && ctype_lower($value),
            self::BASE62_GMP, self::BASE62_BASEX, self::BASE62_BASE64SUBSET => ctype_alnum($value),
        };
    }
}
