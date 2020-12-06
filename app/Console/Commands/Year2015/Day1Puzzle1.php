<?php

namespace App\Console\Commands\Year2015;

class Day1Puzzle1 extends Year2015
{
    protected int $day = 1;
    protected int $part = 1;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $goUp = substr_count($data, '(');
        $goDown = substr_count($data, ')');

        $this->answer = $goUp - $goDown;
    }
}
