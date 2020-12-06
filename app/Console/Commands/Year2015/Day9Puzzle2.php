<?php

namespace App\Console\Commands\Year2015;

class Day9Puzzle2 extends Day9Puzzle1
{
    protected int $part = 2;

    protected function calculateTravellingSalesman($distances, $startingCity, $citiesToVisit)
    {
        $maxDistance = null;
        foreach ($citiesToVisit as $nextCity) {
            $nextCitiesToVisit = array_filter($citiesToVisit, function ($city) use ($nextCity) {
                return $city != $nextCity;
            });

            $thisDistance = $startingCity
                ? $distances[$startingCity][$nextCity]
                : 0;

            $calculated = $thisDistance + $this->calculateTravellingSalesman($distances, $nextCity, $nextCitiesToVisit);

            $maxDistance = is_null($maxDistance)
                ? $calculated
                : max($maxDistance, $calculated);
        }

        return $maxDistance;
    }
}
