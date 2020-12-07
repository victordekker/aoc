<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day7Puzzle1 extends Year2020
{
    protected int $day = 7;
    protected int $part = 1;

    const TARGET = 'shiny gold';

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $bagSpecification = $this->explodePerLine($this->normalizeData($data))
            ->mapWithKeys(function (string $line) {
                return $this->parseLine($line);
            });

        $toAdd = $this->getBagsThatCanContain($bagSpecification, collect([static::TARGET]));
        $canContainTargetBag = collect();

        while ($toAdd->isNotEmpty()) {
            $canContainTargetBag = $canContainTargetBag->merge($toAdd)->unique();
            $toAdd = $this->getBagsThatCanContain($bagSpecification, $toAdd);
        }

        $this->answer = $canContainTargetBag->count();
    }

    protected function parseLine(string $line)
    {
        list($key, $value) = explode (' contain ', $line);

        if ($value == 'no other') {
            return [$key => []];
        }

        return [$key => explode(', ', $value)];
    }

    protected function normalizeData(string $data)
    {
        $data = str_replace(['.', ' bags', ' bag'], [''], $data);
        $data = preg_replace('/\d+\s/', '', $data); // Also get rid of the numbers, since they are not needed for part 1
        return $data;
    }

    protected function getBagsThatCanContain(Collection $bagSpecification, Collection $targetBags)
    {
        return $bagSpecification
            ->filter(function ($bags) use ($targetBags) {
                return $targetBags->intersect($bags)->isNotEmpty();
            })
            ->keys();
    }
}
