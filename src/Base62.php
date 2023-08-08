<?php

declare(strict_types=1);

namespace Codeup\Encoding;

use Codeup\Encoding\Codec\Chain\UuidToBase62;
use Codeup\Encoding\Codec\IntegerToBase62;
use Codeup\Encoding\Codec\StringToBase62;

class Base62
{
    /**
     * @var array
     */
    private static array $instances = [];

    /**
     * @param string $class
     * @return object
     */
    private static function makeOrGetInstance(string $class): object
    {
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }
        return self::$instances[$class];
    }

    /**
     * @return Encoder
     */
    public static function getEncoder(): Encoder
    {
        return self::makeOrGetInstance(StringToBase62::class);
    }

    /**
     * @return Decoder
     */
    public static function getDecoder(): Decoder
    {
        return self::makeOrGetInstance(StringToBase62::class);
    }

    /**
     * @return Encoder
     */
    public static function getIntegerEncoder(): Encoder
    {
        return self::makeOrGetInstance(IntegerToBase62::class);
    }

    /**
     * @return Decoder
     */
    public static function getIntegerDecoder(): Decoder
    {
        return self::makeOrGetInstance(IntegerToBase62::class);
    }

    /**
     * @return Encoder
     */
    public static function getUuidEncoder(): Encoder
    {
        return self::makeOrGetInstance(UuidToBase62::class);
    }

    /**
     * @return Decoder
     */
    public static function getUuidDecoder(): Decoder
    {
        return self::makeOrGetInstance(UuidToBase62::class);
    }

    /**
     * @return Compact\Base62
     */
    private static function makeOrGetBasicCompact(): Compact\Base62
    {
        $instanceName = 'basicCompactor';
        if (!isset(self::$instances[$instanceName])) {
            self::$instances[$instanceName] = Compact\Base62::makeBasicCompact();
        }
        return self::$instances[$instanceName];
    }

    /**
     * @return Compactor
     */
    public static function getCompactor(): Compactor
    {
        return self::makeOrGetBasicCompact();
    }

    /**
     * @return Decompactor
     */
    public static function getDecompactor(): Decompactor
    {
        return self::makeOrGetBasicCompact();
    }

    /**
     * @return Compact\Base62
     */
    private static function makeOrGetUuidCompact(): Compact\Base62
    {
        $instanceName = 'uuidCompactor';
        if (!isset(self::$instances[$instanceName])) {
            self::$instances[$instanceName] = Compact\Base62::makeUuidCompact();
        }
        return self::$instances[$instanceName];
    }

    /**
     * @return Compactor
     */
    public static function getUuidCompactor(): Compactor
    {
        return self::makeOrGetUuidCompact();
    }

    /**
     * @return Decompactor
     */
    public static function getUuidDecompactor(): Decompactor
    {
        return self::makeOrGetUuidCompact();
    }
}
