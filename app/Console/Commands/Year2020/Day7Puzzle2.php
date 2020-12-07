<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day7Puzzle2 extends Day7Puzzle1
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
        $bagSpecification = $this->explodePerLine($this->normalizeData($data))
            ->mapWithKeys(function (string $line) {
                return $this->parseLine($line);
            });

        $this->answer = $this->getAmountOfBags($bagSpecification, static::TARGET);
    }

    protected function parseLine(string $line)
    {
        list($key, $value) = explode (' contain ', $line);

        if ($value == 'no other') {
            return [$key => collect()];
        }

        return [$key => collect(explode(', ', $value))->map(function ($value) {
            $parts = explode(' ', $value, 2);
            return [
                'amount' => $parts[0],
                'color' => $parts[1],
            ];
        })];
    }

    protected function normalizeData(string $data)
    {
        $data = str_replace(['.', ' bags', ' bag'], [''], $data);
        return $data;
    }

    protected function getAmountOfBags(Collection $bagSpecification, $target)
    {
        $specs = $bagSpecification[$target] ?? null;

        if (! $specs instanceof Collection) {
            return 0;
        }

        return $specs
            ->map(function ($spec) use ($bagSpecification) {
                return $spec['amount'] + ($spec['amount'] * $this->getAmountOfBags($bagSpecification, $spec['color']));
            })
            ->sum();
    }
}
