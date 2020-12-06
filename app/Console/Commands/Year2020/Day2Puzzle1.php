<?php

namespace App\Console\Commands\Year2020;

class Day2Puzzle1 extends Year2020
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
            ->map(function ($line) {
                list($rules, $password) = explode(': ', $line, 2);
                list ($minMax, $letter) = explode(' ', $rules, 2);
                list ($min, $max) = explode('-', $minMax, 2);

                return [
                    'password' => $password,
                    'letter' => $letter,
                    'min' => $min,
                    'max' => $max,
                ];
            })
            ->filter(function ($line) {
                return $this->isValid($line['password'], $line['letter'], $line['min'], $line['max']);
            })
            ->count();
    }

    /**
     * @param $password
     * @param $letter
     * @param $min
     * @param $max
     *
     * @return bool
     */
    protected function isValid($password, $letter, $min, $max)
    {
        $count = substr_count($password, $letter);
        return $count >= $min && $count <= $max;
    }
}
