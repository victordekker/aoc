<?php

namespace App\Console\Commands\Year2015;

class Day12Puzzle2 extends Day12Puzzle1
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
        $this->answer = $this->getNumber(json_decode($data));
    }

    protected function getNumber($item)
    {
        if (is_int($item)) {
            return $item;
        }

        if (is_array($item)) {
            return collect($item)
                ->map(function ($item) {
                    return $this->getNumber($item);
                })
                ->sum();
        }

        if (! is_object($item)) {
            return 0;
        }

        if ($this->isRed($item)) {
            return 0;
        }

        return $this->getNumber(collect($item)->values()->toArray());
    }

    protected function isRed(object $item)
    {
        return collect($item)->contains(function ($item) {
            return $item === 'red';
        });
    }
}
