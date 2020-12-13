<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day13Puzzle2 extends Day13Puzzle1
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
        list ($timestamp, $busses) = $this->parseData($data);

        $busses = $busses->filter(function ($bus) {
            return $bus != 'x';
        });

        $timestamp = 100000000000000;

        while (! $this->validateTimestamp($timestamp, $busses)) {
            $timestamp++;
        }

        $this->answer = $timestamp;
    }

    protected function validateTimestamp($timestamp, Collection $busses)
    {
        foreach ($busses as $key => $bus) {
            if (($timestamp + $key) % $bus != 0) {
                return false;
            }
        }

        return true;
    }
}
