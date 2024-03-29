<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec as EncodingStrategy;
use PHPUnit\Framework\TestCase;

class ChainTest extends TestCase
{
    /**
     * @test
     */
    public function encode_withoutStrategies()
    {
        // prepare
        $plainData = uniqid('data');
        $classUnderTest = new Chain();

        // test
        $result = $classUnderTest->encode($plainData);

        // verify
        $this->assertSame($plainData, $result);
    }

    /**
     * @test
     */
    public function decode_withoutStrategies()
    {
        // prepare
        $plainData = uniqid('data');
        $classUnderTest = new Chain();

        // test
        $result = $classUnderTest->decode($plainData);

        // verify
        $this->assertSame($plainData, $result);
    }

    /**
     * @test
     */
    public function encode_withStrategies()
    {
        // prepare
        $plainData = uniqid('data');
        $encoded1Data = uniqid('data');
        $encoded2Data = uniqid('data');
        $strategy1Mock = $this->createMock(EncodingStrategy::class);
        $strategy1Mock->expects($this->once())
            ->method('encode')
            ->with($plainData)
            ->willReturn($encoded1Data);
        $strategy2Mock = $this->createMock(EncodingStrategy::class);
        $strategy2Mock->expects($this->once())
            ->method('encode')
            ->with($encoded1Data)
            ->willReturn($encoded2Data);
        $classUnderTest = new Chain();
        /** @var EncodingStrategy $strategy1Mock */
        /** @var EncodingStrategy $strategy2Mock */
        $classUnderTest
            ->append($strategy1Mock)
            ->append($strategy2Mock);

        // test
        $result = $classUnderTest->encode($plainData);

        // verify
        $this->assertSame($encoded2Data, $result);
    }

    /**
     * @test
     */
    public function decode_withStrategies()
    {
        // prepare
        $plainData = uniqid('data');
        $encoded1Data = uniqid('data');
        $encoded2Data = uniqid('data');
        $strategy1Mock = $this->createMock(EncodingStrategy::class);
        $strategy1Mock->expects($this->once())
            ->method('decode')
            ->with($encoded1Data)
            ->willReturn($plainData);
        $strategy2Mock = $this->createMock(EncodingStrategy::class);
        $strategy2Mock->expects($this->once())
            ->method('decode')
            ->with($encoded2Data)
            ->willReturn($encoded1Data);
        $classUnderTest = new Chain();
        /** @var EncodingStrategy $strategy1Mock */
        /** @var EncodingStrategy $strategy2Mock */
        $classUnderTest
            ->append($strategy1Mock)
            ->append($strategy2Mock);

        // test
        $result = $classUnderTest->decode($encoded2Data);

        // verify
        $this->assertSame($plainData, $result);
    }

    /**
     * @test
     */
    public function encode_withInvertedStrategy()
    {
        // prepare
        $plainData = uniqid('data');
        $encoded1Data = uniqid('data');
        $encoded2Data = uniqid('data');
        $strategy1Mock = $this->createMock(EncodingStrategy::class);
        $strategy1Mock->expects($this->once())
            ->method('decode')
            ->with($plainData)
            ->willReturn($encoded1Data);
        $strategy2Mock = $this->createMock(EncodingStrategy::class);
        $strategy2Mock->expects($this->once())
            ->method('encode')
            ->with($encoded1Data)
            ->willReturn($encoded2Data);
        $classUnderTest = new Chain();
        /** @var EncodingStrategy $strategy1Mock */
        /** @var EncodingStrategy $strategy2Mock */
        $classUnderTest
            ->appendInverted($strategy1Mock)
            ->append($strategy2Mock);

        // test
        $result = $classUnderTest->encode($plainData);

        // verify
        $this->assertSame($encoded2Data, $result);
    }

    /**
     * @test
     */
    public function decode_withInvertedStrategy()
    {
        // prepare
        $plainData = uniqid('data');
        $encoded1Data = uniqid('data');
        $encoded2Data = uniqid('data');
        $strategy1Mock = $this->createMock(EncodingStrategy::class);
        $strategy1Mock->expects($this->once())
            ->method('encode')
            ->with($encoded1Data)
            ->willReturn($plainData);
        $strategy2Mock = $this->createMock(EncodingStrategy::class);
        $strategy2Mock->expects($this->once())
            ->method('decode')
            ->with($encoded2Data)
            ->willReturn($encoded1Data);
        $classUnderTest = new Chain();
        /** @var EncodingStrategy $strategy1Mock */
        /** @var EncodingStrategy $strategy2Mock */
        $classUnderTest
            ->appendInverted($strategy1Mock)
            ->append($strategy2Mock);

        // test
        $result = $classUnderTest->decode($encoded2Data);

        // verify
        $this->assertSame($plainData, $result);
    }
}
