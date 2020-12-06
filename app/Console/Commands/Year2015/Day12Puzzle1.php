<?php

namespace App\Console\Commands\Year2015;

class Day12Puzzle1 extends Year2015
{
    protected int $day = 12;
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
        preg_match_all('/(-?[0-9]+)/', $data, $matches);

        $this->answer = array_sum($matches[0]);
    }
}
