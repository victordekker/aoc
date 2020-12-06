<?php

namespace App\Console\Commands\Year2020;

class Day3Puzzle2 extends Day3Puzzle1
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
        $map = $this->explodePerLine($data)->map(function ($line) {
            return str_split($line);
        });

        $this->answer = collect([
            [1, 1],
            [3, 1],
            [5, 1],
            [7, 1],
            [1, 2],
        ])
            // Calculate the amount of trees for every move-set
            ->map(function ($oneMove) use ($map) {
                return $this->runThroughOnce($map, $oneMove);
            })

            // Multiply all numbers
            ->reduce(function ($carry, $item) {
                return $carry * $item;
            }, 1);
    }
}
