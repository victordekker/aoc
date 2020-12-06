<?php

namespace App\Console\Commands\Year2015;

class Day2Puzzle1 extends Year2015
{
    protected int $day = 2;
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
        $this->answer = $this->explodePerLine($data)
            ->map(function ($present) {
                return explode('x', $present);
            })
            ->map(function ($dimensions) {
                $l = (int) $dimensions[0];
                $w = (int) $dimensions[1];
                $h = (int) $dimensions[2];
                return (2 * $l * $w) + (2 * $w * $h) + (2 * $h * $l) + min($l * $w, $w * $h, $h * $l);
            })
            ->sum();
    }
}
