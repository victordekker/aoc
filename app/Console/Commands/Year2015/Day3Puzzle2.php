<?php

namespace App\Console\Commands\Year2015;

class Day3Puzzle2 extends Day3Puzzle1
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
        // We'll keep a list of visited houses with how many times they are visited.
        $visitedHouses = collect(['0x0' => 1]);
        $santaLocation = [0, 0]; // x, y coords
        $robotLocation = [0, 0]; // x, y coords

        foreach (str_split($data) as $i => $step) {
            $useRobot = $i % 2;

            // Do the step
            $newLocation = $this->doStep($useRobot ? $robotLocation : $santaLocation, $step);

            // Record that the house is visited
            $key = $this->locationToKey($newLocation);
            $visitedHouses[$key] = ($visitedHouses[$key] ?? 0) + 1;

            if ($useRobot) {
                $robotLocation = $newLocation;
            } else {
                $santaLocation = $newLocation;
            }
        }

        // Count how many houses are visited more than 0 times.
        $this->answer = $visitedHouses->filter()->count();
    }
}
