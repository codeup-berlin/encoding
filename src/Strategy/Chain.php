<?php
namespace Codeup\Encoding\Strategy;

class Chain implements \Codeup\Encoding\Strategy
{
    /**
     * @var \Codeup\Encoding\Strategy[]
     */
    private $strategies = [];

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        /** @var \Codeup\Encoding\Strategy $strategy */
        foreach ($this->strategies as $strategy) {
            $data = $strategy->encode($data);
        }
        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string
    {
        /** @var \Codeup\Encoding\Strategy $strategy */
        foreach (array_reverse($this->strategies) as $strategy) {
            $data = $strategy->decode($data);
        }
        return $data;
    }

    /**
     * @param \Codeup\Encoding\Strategy $strategy
     * @return Chain
     */
    public function append(\Codeup\Encoding\Strategy $strategy)
    {
        $this->strategies[] = $strategy;
        return $this;
    }
}
