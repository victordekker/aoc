<?php

namespace App\Console\Commands\Year2020;

class Day4Puzzle1 extends Year2020
{
    protected int $day = 4;
    protected int $part = 1;

    const REQUIRED_FIELDS = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid']; // Not included: cid

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $this->answer = $this->explodePerEmptyLine($data)
            ->map(function (string $passport) {
                return $this->parsePassport($passport);
            })
            ->filter(function (array $passport) {
                return $this->isValid($passport);
            })
            ->count();
    }

    protected function parsePassport(string $passport)
    {
        $passport = str_replace("\n", ' ', $passport);
        $passport = array_filter(explode(' ', $passport));

        return collect($passport)->mapWithKeys(function ($item) {
            list($key, $value) = explode(':', $item);
            return ($value !== '') ? [$key => $value] : null;
        })->filter()->toArray();
    }

    protected function isValid(array $passport)
    {
        $required = static::REQUIRED_FIELDS;

        return collect($passport)
            ->keys()
            ->intersect($required)
            ->count() === count($required);
    }
}
