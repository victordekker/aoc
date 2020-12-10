<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day9Puzzle1 extends Year2020
{
    protected int $day = 9;
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
        $data = $this->explodePerLine($data)->map(function ($line) {
            return (int) $line; // Cast to integer, just to make sure
        });

        $this->answer = $this->findFirstInvalidNumber($data, 25);
    }

    protected function findFirstInvalidNumber(Collection $numbers, $length)
    {
        $i = $length;

        while ($this->isValid($numbers[$i], $numbers->slice($i - $length, $length))) {
            $i++;
        }

        return $numbers[$i];
    }

    protected function isValid($target, Collection $previousNumbers)
    {
        return $previousNumbers
            ->values()

            // Better way of cross joining
            ->flatMap(function ($number, $index) use ($previousNumbers) {
                return $previousNumbers->slice($index + 1)->crossJoin([$number]);
            })

            // Only take the pairs whose sum is the target
            ->filter(function ($pair) use ($target) {
                return $pair[0] + $pair[1] === $target;
            })

            ->isNotEmpty();
    }
}
