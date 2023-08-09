<?php

declare(strict_types=1);

namespace Codeup\Encoding\Codec;

use Codeup\Encoding\Alphabet;

class BaseConv extends Chain
{
    /**
     * @param string $sourceAlphabetValue
     * @param string $targetAlphabetValue
     */
    public function __construct(string $sourceAlphabetValue, string $targetAlphabetValue)
    {
        if ($sourceAlphabetValue !== $targetAlphabetValue) {
            if (Alphabet::BINARY->value === $sourceAlphabetValue) {
                // bin -> hex -> dec -> target
                $this->append(new BinToHex());
                $this->append(new AnyToDecimal(Alphabet::HEX->value));
                $this->appendInverted(new AnyToDecimal($targetAlphabetValue));
            } elseif (Alphabet::BINARY->value === $targetAlphabetValue) {
                // source -> dec -> hex -> bin
                $this->append(new AnyToDecimal($sourceAlphabetValue));
                $this->appendInverted(new AnyToDecimal(Alphabet::HEX->value));
                $this->appendInverted(new BinToHex());
            } else {
                // source -> dec -> target
                $this->append(new AnyToDecimal($sourceAlphabetValue));
                $this->appendInverted(new AnyToDecimal($targetAlphabetValue));
            }
        }
    }

    /**
     * @param Alphabet $sourceAlphabet
     * @param Alphabet $targetAlphabet
     * @return self
     */
    public static function makeAnyToAny(Alphabet $sourceAlphabet, Alphabet $targetAlphabet): self
    {
        return new self($sourceAlphabet->value, $targetAlphabet->value);
    }

    /**
     * @param Alphabet $sourceAlphabet
     * @return self
     */
    public static function makeAnyToBin(Alphabet $sourceAlphabet): self
    {
        return new self($sourceAlphabet->value, Alphabet::BINARY->value);
    }

    /**
     * @param Alphabet $targetAlphabet
     * @return self
     */
    public static function makeBinToAny(Alphabet $targetAlphabet): self
    {
        return new self(Alphabet::BINARY->value, $targetAlphabet->value);
    }
}
