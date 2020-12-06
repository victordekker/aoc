<?php

namespace App\Console\Commands\Year2015;

class Day1Puzzle2 extends Day1Puzzle1
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
        $floor = 0;

        foreach (str_split($data) as $i => $character) {
            if ($character == '(') {
                $floor++;
            } elseif ($character == ')') {
                $floor--;
            }

            if ($floor < 0) {
                $this->answer = $i + 1;
                break;
            }
        }
    }
}
