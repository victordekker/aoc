<?php

namespace App\Console\Commands\Year2015;

use Illuminate\Support\Collection;

class Day14Puzzle1 extends Year2015
{
    protected int $day = 14;
    protected int $part = 1;

    const RACE_DURATION = 2503;

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

        $this->answer = $allStats
            ->map(function ($stats) {
                return $this->calculateDistanceTravelled($stats, static::RACE_DURATION);
            })
            ->sort()
            ->last();
    }

    protected function parseData(string $line)
    {
        preg_match('/(\w+) can fly (\d+) km\/s for (\d+) seconds, but then must rest for (\d+) seconds\./', $line, $matches);

        return [$matches[1] => [
            'speed' => $matches[2],
            'flyTime' => $matches[3],
            'restTime' => $matches[4],
        ]];
    }

    protected function calculateDistanceTravelled(array $stats, int $duration)
    {
        $fullRunTime = $stats['flyTime'] + $stats['restTime'];

        $fullRunCount = floor($duration / $fullRunTime);
        $remainingFlyDuration = min($duration % $fullRunTime, $stats['flyTime']);

        $flyTime = ($fullRunCount * $stats['flyTime']) + $remainingFlyDuration;

        return $flyTime * $stats['speed'];
    }
}
