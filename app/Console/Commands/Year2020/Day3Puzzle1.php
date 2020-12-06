<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day3Puzzle1 extends Year2020
{
    protected int $day = 3;
    protected int $part = 1;

    const ONE_MOVE = [3, 1];
    const TREE = '#';

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $map = $this->explodePerLine($data)->map(function ($line) {
            return str_split($line);
        });

        $this->answer = $this->runThroughOnce($map, static::ONE_MOVE);
    }

    protected function runThroughOnce(Collection $map, $oneMove)
    {
        $height = $map->count();
        $width = count($map->first());

        $location = [0, 0];
        $encounteredTrees = 0;

        while ($location[1] < $height) {
            $location = $this->doMove($location, $oneMove, $width);

            if ($this->onTree($location, $map)) {
                $encounteredTrees++;
            }
        }

        return $encounteredTrees;
    }

    protected function doMove($location, $move, $width)
    {
        $location[0] = ($location[0] + $move[0]) % $width;
        $location[1] = $location[1] + $move[1];

        return $location;
    }

    protected function onTree($location, $map)
    {
        return ($map[$location[1]][$location[0]] ?? null) == static::TREE;
    }
}
