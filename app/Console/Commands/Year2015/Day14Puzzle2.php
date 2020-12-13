<?php

namespace App\Console\Commands\Year2015;

class Day14Puzzle2 extends Day14Puzzle1
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
        $allStats = $this->explodePerLine($data)->mapWithKeys(function ($line) {
            return $this->parseData($line);
        });

        $reindeerScores = $allStats->map(function ($stats) {
            return 0;
        });

        $second = 1;
        while ($second <= static::RACE_DURATION) {
            $reindeerLeads = $allStats
                ->map(function ($stats) use ($second) {
                    return $this->calculateDistanceTravelled($stats, $second);
                })
                ->sort();

            $reindeerLead = $reindeerLeads->last();
            $reindeerInLead = $reindeerLeads->filter(function ($position) use ($reindeerLead) {
                return $position === $reindeerLead;
            })->keys();

            foreach ($reindeerInLead as $reindeer) {
                $reindeerScores[$reindeer] = $reindeerScores[$reindeer] + 1;
            }

            $second++;
        }

        $this->answer = $reindeerScores->sort()->last();
    }
}
