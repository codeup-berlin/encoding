<?php

declare(strict_types=1);

namespace Codeup\Encoding;

use Codeup\Encoding\Codec\Chain\UuidToBase62;
use Codeup\Encoding\Codec\IntegerToBase62;
use Codeup\Encoding\Codec\StringToBase62;
use PHPUnit\Framework\TestCase;

class Base62Test extends TestCase
{
    /**
     * @test
     */
    public function getStringEncoder_encodeValidInt()
    {
        // test
        $encoder = Base62::getEncoder();
        $encoded = $encoder->encode('1000');

        // verify
        $this->assertInstanceOf(StringToBase62::class, $encoder);
        $this->assertSame('tqd3A', $encoded);
    }

    /**
     * @test
     */
    public function getStringDecoder_decodeValidInt()
    {
        // test
        $decoder = Base62::getDecoder();
        $decoded = $decoder->decode('tqd3A');

        // verify
        $this->assertInstanceOf(StringToBase62::class, $decoder);
        $this->assertSame('1000', $decoded);
    }

    /**
     * @test
     */
    public function getIntegerEncoder_encodeValidInt()
    {
        // test
        $encoder = Base62::getIntegerEncoder();
        $encoded = $encoder->encode('1000');

        // verify
        $this->assertInstanceOf(IntegerToBase62::class, $encoder);
        $this->assertSame('+G8', $encoded);
    }

    /**
     * @test
     */
    public function getIntegerDecoder_decodeValidInt()
    {
        // test
        $decoder = Base62::getIntegerDecoder();
        $decoded = $decoder->decode('+G8');

        // verify
        $this->assertInstanceOf(IntegerToBase62::class, $decoder);
        $this->assertSame('1000', $decoded);
    }

    /**
     * @test
     */
    public function getUuidEncoder_encodeValidUuid()
    {
        // test
        $encoder = Base62::getUuidEncoder();
        $encoded = $encoder->encode('21569827-e5d1-4fcc-8374-fd7950553eed');

        // verify
        $this->assertInstanceOf(UuidToBase62::class, $encoder);
        $this->assertSame('10uJs5dWKec9MulCf2wXa1', $encoded);
    }

    /**
     * @test
     */
    public function getUuidDecoder_decompactValidUuid()
    {
        // test
        $decoder = Base62::getUuidDecoder();
        $decoded = $decoder->decode('10uJs5dWKec9MulCf2wXa1');

        // verify
        $this->assertInstanceOf(UuidToBase62::class, $decoder);
        $this->assertSame('21569827-e5d1-4fcc-8374-fd7950553eed', $decoded);
    }

    /**
     * @test
     */
    public function getCompactor_compactValid()
    {
        // test
        $compactor = Base62::getCompactor();
        $compacted = $compactor->compact('21569827-e5d1-4fcc-8374-fd7950553eed');

        // verify
        $this->assertInstanceOf(Compact\Base62::class, $compactor);
        $this->assertSame('tnX2GBjvIjQnB6HsPFKeu3YVjmTWHy9RCJXrSiz31x67dVbw', $compacted);
    }

    /**
     * @test
     */
    public function getDecompactor_decompactValid()
    {
        // test
        $decompactor = Base62::getDecompactor();
        $decompacted = $decompactor->decompact('tnX2GBjvIjQnB6HsPFKeu3YVjmTWHy9RCJXrSiz31x67dVbw');

        // verify
        $this->assertInstanceOf(Compact\Base62::class, $decompactor);
        $this->assertSame('21569827-e5d1-4fcc-8374-fd7950553eed', $decompacted);
    }

    /**
     * @test
     */
    public function getDecompactor_decompactValidInt()
    {
        // test
        $decompactor = Base62::getDecompactor();
        $decompacted = $decompactor->decompact('+G8');

        // verify
        $this->assertInstanceOf(Compact\Base62::class, $decompactor);
        $this->assertSame(1000, $decompacted);
    }

    /**
     * @test
     */
    public function getUuidCompactor_compactValidUuid()
    {
        // test
        $compactor = Base62::getUuidCompactor();
        $compacted = $compactor->compact('21569827-e5d1-4fcc-8374-fd7950553eed');

        // verify
        $this->assertInstanceOf(Compact\Base62::class, $compactor);
        $this->assertSame('10uJs5dWKec9MulCf2wXa1', $compacted);
    }

    /**
     * @test
     */
    public function getUuidDecompactor_decompactValidUuid()
    {
        // test
        $decompactor = Base62::getUuidDecompactor();
        $decompacted = $decompactor->decompact('10uJs5dWKec9MulCf2wXa1');

        // verify
        $this->assertInstanceOf(Compact\Base62::class, $decompactor);
        $this->assertSame('21569827-e5d1-4fcc-8374-fd7950553eed', $decompacted);
    }

    /**
     * @test
     */
    public function getUuidDecompactor_decompactValidInt()
    {
        // test
        $decompactor = Base62::getUuidDecompactor();
        $decompacted = $decompactor->decompact('+G8');

        // verify
        $this->assertInstanceOf(Compact\Base62::class, $decompactor);
        $this->assertSame(1000, $decompacted);
    }
}
