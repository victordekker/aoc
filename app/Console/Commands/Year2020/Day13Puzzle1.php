<?php

namespace App\Console\Commands\Year2020;

class Day13Puzzle1 extends Day12Puzzle1
{
    protected int $day = 13;
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
        list ($timestamp, $busses) = $this->parseData($data);

        $first = $busses
            ->filter(function ($bus) {
                return $bus != 'x';
            })
            ->map(function ($bus) use ($timestamp) {
                return ['id' => $bus, 'timestamp' => ceil($timestamp / $bus) * $bus];
            })
            ->sortBy('timestamp')
            ->first();

        $this->answer = $first['id'] * ($first['timestamp'] - $timestamp);
    }

    protected function parseData(string $data)
    {
        list ($timestamp, $busses) = explode("\n", $data);
        return [(int) $timestamp, collect(explode(',', $busses))];
    }
}
