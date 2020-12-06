<?php

namespace App\Console\Commands\Year2020;

class Day1Puzzle1 extends Year2020
{
    protected int $day = 1;
    protected int $part = 1;

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
        $data = $this->explodePerLine($data)->map(function ($number) {
            return (int) $number; // Make sure we have integers
        });

        $this->answer = $data
            // Easy way of cross joining, will lead to duplicate pairs and pairs of 2 the same records.
//            ->crossJoin($data)

            // Better way of cross joining
            ->flatMap(function ($number, $index) use ($data) {
                return $data->slice($index + 1)->crossJoin([$number]);
            })

            // Only take the pairs whose sum is the target
            ->filter(function ($pair) {
                return $pair[0] + $pair[1] === static::TARGET;
            })

            // Calculate the product of these pairs
            ->map(function ($pair) {
                return $pair[0] * $pair[1];
            })

            // Get the first result
            ->first();
    }
}
