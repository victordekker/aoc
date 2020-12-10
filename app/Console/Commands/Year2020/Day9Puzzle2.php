<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day9Puzzle2 extends Day9Puzzle1
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
        $data = $this->explodePerLine($data)->map(function ($line) {
            return (int) $line; // Cast to integer, just to make sure
        });

        $target = $this->findFirstInvalidNumber($data, 25);

        $contiguousSet = $this->findContiguousSet($data, $target)->sort();

        $this->answer = $contiguousSet->first() + $contiguousSet->last();
    }

    protected function findContiguousSet(Collection $numbers, $target)
    {
        $i = 0; // Start of set
        $length = 0; // End of set
        $set = collect();

        while ($set->sum() !== $target) {
            if ($set->sum() < $target) {
                $length++;
            } else {
                $i++;
                $length = 1;
            }

            if ($i + $length >= $numbers->count()) {
                $i++;
                $length = 1;
            }

            if ($i >= $numbers->count()) {
                return collect();
            }

            $set = $numbers->slice($i, $length);
            $this->output->writeln("Trying set {$i} - " . ($i + $length));
        }

        return $set;
    }
}
