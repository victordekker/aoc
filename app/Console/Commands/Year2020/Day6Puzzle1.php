<?php

namespace App\Console\Commands\Year2020;

class Day6Puzzle1 extends Year2020
{
    protected int $day = 6;
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
        $this->answer = $this->explodePerEmptyLine($data)
            ->map(function (string $group) {
                return $this->getCountForGroup($group);
            })
            ->sum();
    }

    protected function getCountForGroup(string $group)
    {
        $group = str_replace("\n", '', $group);

        return collect(str_split($group))
            ->unique()
            ->count();
    }
}
