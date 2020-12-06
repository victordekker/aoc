<?php

namespace App\Console\Commands\Year2020;

class Day5Puzzle2 extends Day5Puzzle1
{
    protected int $part = 2;

    protected function solve(string $data)
    {
        $seats = $this->explodePerLine($data)
            ->map(function (string $line) {
                return $this->parseLine($line);
            })
            ->pluck('id')
            ->sort();

        $minSeat = $seats->first();
        $maxSeat = $seats->last();

        // Compare our (sorted) list of seat ID's with a range array [min ... max].
        $this->answer = collect(range($minSeat, $maxSeat))
            ->diff($seats) // Get all numbers that are in the range array and not in the seats list.
            ->first(); // Should be 1 answer according to the puzzle.
    }
}
