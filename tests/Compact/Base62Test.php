<?php

declare(strict_types=1);

namespace Codeup\Encoding\Compact;

use Codeup\Encoding\Codec\IntegerToBase62;
use Codeup\Encoding\Codec\StringToBase62;
use PHPUnit\Framework\TestCase;

class Base62Test extends TestCase
{
    public static function provideCompactTestCases(): array
    {
        return [
            'zero' => [0, 0],
            'one positive int' => [1, 1],
            'one negative int' => [-1, -1],
            'one positive string' => ['1', 'n'],
            'one negative string' => ['-1', '30b'],
            'single digit int' => [9, 9],
            'single digit string' => ['9', 'v'],
            'double digit int' => [99, 99],
            'double digit string' => ['99', '3oH'],
            'triple digit int' => [999, 999],
            'triple digit string' => ['999', 'Fjb7'],
            'minimal compactable number' => [1000, '+G8'],
            'minimal compactable negative number' => [-1000, '-G8'],
        ];
    }

    /**
     * @return Base62
     */
    private function getClassUnderTest(): Base62
    {
        return new Base62(new StringToBase62(), new IntegerToBase62());
    }

    /**
     * @test
     * @dataProvider provideCompactTestCases
     */
    public function compact(int|string $plain, int|string $expected)
    {
        $classUnderTest = $this->getClassUnderTest();
        $result = $classUnderTest->compact($plain);
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider provideCompactTestCases
     */
    public function decompact(int|string $expected, int|string $compact)
    {
        $classUnderTest = $this->getClassUnderTest();
        $result = $classUnderTest->decompact($compact);
        $this->assertSame($expected, $result);
    }
}
