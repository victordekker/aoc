<?php

namespace App\Console\Commands\Year2015;

class Day4Puzzle1 extends Year2015
{
    protected int $day = 4;
    protected int $part = 1;
    protected bool $inputIsFilename = false;

    const TARGET = '00000';

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $i = 0;
        $targetLength = strlen(static::TARGET);

        while (substr($this->getHash($data, $i), 0, $targetLength) !== static::TARGET) {
            $i++;
        }

        $this->answer = $i;
    }

    protected function getHash($secretKey, $number)
    {
        return md5($secretKey . $number);
    }
}
