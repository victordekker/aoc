<?php

namespace App\Console\Commands\Year2015;

class Day3Puzzle1 extends Year2015
{
    protected int $day = 3;
    protected int $part = 1;

    const NORTH = '^'; // negative Y
    const SOUTH = 'v'; // positive Y
    const EAST = '>'; // positive X
    const WEST = '<'; // negative X

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        // We'll keep a list of visited houses with how many times they are visited.
        $visitedHouses = collect(['0x0' => 1]); // First house is already visited
        $location = [0, 0]; // x, y coords

        foreach (str_split($data) as $step) {
            // Do the step
            $location = $this->doStep($location, $step);

            // Record that the house is visited
            $key = $this->locationToKey($location);
            $visitedHouses[$key] = ($visitedHouses[$key] ?? 0) + 1;
        }

        // Count how many houses are visited more than 0 times.
        $this->answer = $visitedHouses->filter()->count();
    }

    protected function doStep($location, $step)
    {
        switch ($step) {
            case static::NORTH:
                $location[1] = $location[1] - 1;
                break;
            case static::SOUTH:
                $location[1] = $location[1] + 1;
                break;
            case static::EAST:
                $location[0] = $location[0] + 1;
                break;
            case static::WEST:
                $location[0] = $location[0] - 1;
                break;
        }

        return $location;
    }

    protected function locationToKey($location)
    {
        return $location[0] . 'x' . $location[1];
    }
}
