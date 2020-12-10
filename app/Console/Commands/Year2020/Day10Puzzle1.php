<?php

namespace App\Console\Commands\Year2020;

class Day10Puzzle1 extends Year2020
{
    protected int $day = 10;
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
        $data = $this->explodePerLine($data)
            ->map(function ($line) {
                return (int) $line; // Cast to integer, just to make sure
            })
            ->sort()
            ->values();

        $data->push($data->last() + 3); // Add the last adapter

        $totalDiffCount = [];

        foreach ($data as $key => $item) {
            $diff = $item - ($data[$key - 1] ?? 0);
            $totalDiffCount[$diff] = ($totalDiffCount[$diff] ?? 0) + 1;
        }

        $this->answer = $totalDiffCount[1] * $totalDiffCount[3];
    }
}
