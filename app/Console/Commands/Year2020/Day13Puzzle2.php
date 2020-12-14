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

        // Remove all X-es
        $busses = $busses->filter(function ($bus) {
            return $bus != 'x';
        });

        // Find the largest bus ID, change all keys to let largest bus ID have key 0 while maintaining the relative key order.
        $busses = $busses->sort();
        $largestBusId = $busses->last();
        $largestBusKey = $busses->keys()->last();
        $busses = $busses->mapWithKeys(function ($bus, $key) use ($largestBusKey) {
            return [$key - $largestBusKey => $bus];
        });

        $timestamp = 100000000000000;

        // Find starting timestamp: closest multiple of $largestBusId greater or equal than $timestamp
        $timestamp = ceil($timestamp / $largestBusId) * $largestBusId;

        while (! $this->validateTimestamp($timestamp, $busses)) {
            $timestamp += $largestBusId;
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
