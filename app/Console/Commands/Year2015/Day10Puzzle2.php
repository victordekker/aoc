<?php

namespace App\Console\Commands\Year2015;

class Day10Puzzle2 extends Day10Puzzle1
{
    protected int $part = 2;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $this->answer = strlen($this->runThrough(50, $data));
    }
}
