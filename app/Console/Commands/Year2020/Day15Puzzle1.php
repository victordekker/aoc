<?php

namespace App\Console\Commands\Year2020;

class Day15Puzzle1 extends Year2020
{
    protected int $day = 15;
    protected int $part = 1;
    protected bool $inputIsFilename = false;

    const TARGET = 2020;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $numbers = $this->getStartingNumbers($data);

        $i = count($numbers);
        $currentNumber = 0;

        while ($i + 1 < static::TARGET) {
            $previousNumber = $currentNumber;
            $currentNumber = isset($numbers[$previousNumber])
                ? $i - $numbers[$previousNumber]
                : 0;
            $numbers[$previousNumber] = $i;
            $i++;
        }

        $this->answer = $currentNumber;
    }

    protected function getStartingNumbers(string $data)
    {
        return array_flip(explode(',', $data));
    }
}
