<?php

namespace App\Console\Commands\Year2020;

class Day2Puzzle2 extends Day2Puzzle1
{
    protected int $part = 2;

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
        if ($max > strlen($password)) {
            return false;
        }

        $letterAtMin = substr($password, $min - 1, 1);
        $letterAtMax = substr($password, $max - 1, 1);

        return ($letterAtMin == $letter || $letterAtMax == $letter) && $letterAtMin != $letterAtMax;
    }
}
