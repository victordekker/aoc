<?php

namespace App\Console\Commands\Year2020;

class Day6Puzzle2 extends Day6Puzzle1
{
    protected int $part = 2;

    protected function getCountForGroup(string $group)
    {
        $lines = $this->explodePerLine($group)
            ->map(function ($line) {
                return str_split($line);
            });

        $answers = collect($lines->shift());

        while ($lines->isNotEmpty()) {
            $line = $lines->shift();
            $answers = $answers->intersect($line);
        }

        return $answers->unique()->count();
    }
}
