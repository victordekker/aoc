<?php

namespace App\Console\Commands\Year2015;

class Day2Puzzle2 extends Day2Puzzle1
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
        $this->answer = $this->explodePerLine($data)
            ->map(function ($present) {
                return explode('x', $present);
            })
            ->map(function ($dimensions) {
                $l = (int) $dimensions[0];
                $w = (int) $dimensions[1];
                $h = (int) $dimensions[2];
                return min(2 * $l + 2 * $w, 2 * $w + 2 * $h, 2 * $h + 2 * $l) + ($l * $w * $h);
            })
            ->sum();
    }
}
