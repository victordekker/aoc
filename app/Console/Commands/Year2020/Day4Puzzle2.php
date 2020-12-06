<?php

namespace App\Console\Commands\Year2020;

class Day4Puzzle2 extends Day4Puzzle1
{
    protected int $part = 2;

    const REQUIRED_FIELDS = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid']; // Not included: cid

    protected function isValid(array $passport)
    {
        if (! parent::isValid($passport)) {
            return false;
        }

        // Extra validation!
        return $this->validateYear($passport['byr'], 1920, 2002) &&
            $this->validateYear($passport['iyr'], 2010, 2020) &&
            $this->validateYear($passport['eyr'], 2020, 2030) &&
            $this->validateHeight($passport['hgt']) &&
            $this->validateHairColor($passport['hcl']) &&
            $this->validateEyeColor($passport['ecl']) &&
            $this->validatePassportId($passport['pid']);
    }

    protected function validateYear($year, $min, $max)
    {
        return preg_match('/^\d{4}$/', $year) && ((int) $year) >= $min && ((int) $year) <= $max;
    }

    protected function validateHeight($height)
    {
        $match = preg_match('/^(\d+)((cm)|(in))$/', $height, $matches);

        if (! $match) {
            return false;
        }

        if ($matches[2] == 'cm') {
            return ((int) $matches[1]) >= 150 && ((int) $matches[1]) <= 193;
        }

        if ($matches[2] == 'in') {
            return ((int) $matches[1]) >= 59 && ((int) $matches[1]) <= 76;
        }

        return false;
    }

    protected function validateHairColor($color)
    {
        return preg_match('/^#[0-9a-f]{6}$/', $color);
    }

    protected function validateEyeColor($color)
    {
        return in_array($color, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
    }

    protected function validatePassportId($id)
    {
        return preg_match('/^\d{9}$/', $id);
    }
}
