<?php

namespace App\Console\Commands\Year2020;

class Day1Puzzle2 extends Day1Puzzle1
{
    protected int $part = 2;

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
            // Easy way of cross joining, will lead to duplicate pairs and pairs of 2 or 3 the same records.
//            ->crossJoin($data, $data)

            // Better way of 3-way cross joining
            ->flatMap(function ($number, $index) use ($data) {
                $subData = $data->slice($index + 1)->values();

                return $subData
                    ->flatMap(function ($number, $index) use ($subData) {
                        return $subData->slice($index + 1)->crossJoin([$number]);
                    })
                    // Only take the pairs whose sum equal or smaller than target (since a 3rd number will be added)
                    ->filter(function ($pair) {
                        return $pair[0] + $pair[1] <= static::TARGET;
                    })
                    ->crossJoin([$number])
                    ->map(function ($pair) {
                        return collect($pair)->flatten()->toArray();
                    });
            })

            // Only take the pairs whose sum is the target
            ->filter(function ($pair) {
                return $pair[0] + $pair[1] + $pair[2] === static::TARGET;
            })

            // Calculate the product of these pairs
            ->map(function ($pair) {
                return $pair[0] * $pair[1] * $pair[2];
            })

            // Get the first result
            ->first();
    }
}
