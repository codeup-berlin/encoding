<?php
namespace Codeup\Encoding\Strategy;

use Codeup\Encoding\Strategy as EncodingStrategy;

class Chain implements EncodingStrategy
{
    /**
     * @var [\Codeup\Encoding\Strategy, bool][]
     */
    private $strategies = [];

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        /** @var \Codeup\Encoding\Strategy $strategy */
        /** @var bool $inverted */
        foreach ($this->strategies as list($strategy, $inverted)) {
            $data = $inverted ? $strategy->decode($data) : $strategy->encode($data);
        }
        return $data;
    }

    /**
     * @param string $data
     * @return string
     * @throws \InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        /** @var \Codeup\Encoding\Strategy $strategy */
        /** @var bool $inverted */
        foreach (array_reverse($this->strategies) as list($strategy, $inverted)) {
            $data = $inverted ? $strategy->encode($data) : $strategy->decode($data);
        }
        return $data;
    }

    /**
     * @param \Codeup\Encoding\Strategy $strategy
     * @return Chain
     */
    public function append(EncodingStrategy $strategy)
    {
        $this->strategies[] = [$strategy, false];
        return $this;
    }

    /**
     * Inverted encoders behave the opposite as the chain.
     * In case the chain is encoded, the inverted encoder will decode and vice versa.
     *
     * @param \Codeup\Encoding\Strategy $strategy
     * @return Chain
     */
    public function appendInverted(EncodingStrategy $strategy)
    {
        $this->strategies[] = [$strategy, true];
        return $this;
    }
}
