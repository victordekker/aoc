<?php

namespace App\Console\Commands\Year2015;

class Day9Puzzle1 extends Year2015
{
    protected int $day = 9;
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
        $distances = [];

        // Parse distances
        $this->explodePerLine($data)->each(function (string $line) use (&$distances) {
            list($locations, $distance) = explode(' = ', $line);
            list($first, $second) = explode(' to ', $locations);

            if (! isset($distances[$first])) {
                $distances[$first] = [];
            }

            if (! isset($distances[$second])) {
                $distances[$second] = [];
            }

            $distances[$first][$second] = $distance;
            $distances[$second][$first] = $distance;
        });

        // Do travelling salesman
        $this->answer = $this->calculateTravellingSalesman($distances, null, array_keys($distances));
    }

    protected function calculateTravellingSalesman($distances, $startingCity, $citiesToVisit)
    {
        $minDistance = null;
        foreach ($citiesToVisit as $nextCity) {
            $nextCitiesToVisit = array_filter($citiesToVisit, function ($city) use ($nextCity) {
                return $city != $nextCity;
            });

            $thisDistance = $startingCity
                ? $distances[$startingCity][$nextCity]
                : 0;

            $calculated = $thisDistance + $this->calculateTravellingSalesman($distances, $nextCity, $nextCitiesToVisit);

            $minDistance = is_null($minDistance)
                ? $calculated
                : min($minDistance, $calculated);
        }

        return $minDistance;
    }
}
