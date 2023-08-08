<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Codec;
use InvalidArgumentException;

class Chain implements Codec
{
    /**
     * @var array<Codec, bool>
     */
    private array $strategies = [];

    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        /** @var Codec $strategy */
        /** @var bool $inverted */
        foreach ($this->strategies as [$strategy, $inverted]) {
            $data = $inverted ? $strategy->decode($data) : $strategy->encode($data);
        }
        return $data;
    }

    /**
     * @param string $data
     * @return string
     * @throws InvalidArgumentException if the passed data can not be decoded
     */
    public function decode(string $data): string
    {
        /** @var Codec $strategy */
        /** @var bool $inverted */
        foreach (array_reverse($this->strategies) as [$strategy, $inverted]) {
            $data = $inverted ? $strategy->encode($data) : $strategy->decode($data);
        }
        return $data;
    }

    /**
     * @param Codec $strategy
     * @return Chain
     */
    public function append(Codec $strategy): static
    {
        $this->strategies[] = [$strategy, false];
        return $this;
    }

    /**
     * Inverted encoders behave the opposite as the chain.
     * In case the chain is encoded, the inverted encoder will decode and vice versa.
     *
     * @param Codec $strategy
     * @return Chain
     */
    public function appendInverted(Codec $strategy): static
    {
        $this->strategies[] = [$strategy, true];
        return $this;
    }
}
