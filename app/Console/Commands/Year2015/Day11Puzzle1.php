<?php

namespace App\Console\Commands\Year2015;

use Illuminate\Support\Collection;

class Day11Puzzle1 extends Year2015
{
    protected int $day = 11;
    protected int $part = 1;
    protected bool $inputIsFilename = false;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $mustContainAnyOf = $this->getStraights();
        $cannotContainAnyOf = collect(['i', 'o', 'l']);

        $data = $this->incrementPassword($data);

        while (! $this->isValid($data, $mustContainAnyOf, $cannotContainAnyOf)) {
            $data = $this->incrementPassword($data);
        }

        $this->answer = $data;
    }

    protected function getStraights()
    {
        return collect(range(97, 120))->map(function ($ascii) {
            return chr($ascii) . chr($ascii + 1) . chr($ascii + 2);
        });
    }

    protected function incrementPassword($password)
    {
        $lastCharacter = substr($password, -1);
        $rest = substr($password, 0, strlen($password) - 1);

        if ($lastCharacter == 'z') {
            return $this->incrementPassword($rest) . 'a';
        }

        // Add 1 to the ascii value of the last character.
        return $rest . chr(ord($lastCharacter) + 1);
    }

    protected function isValid($password, Collection $mustContainAnyOf, Collection $cannotContainAnyOf)
    {
        if (! $this->containsAnyOf($password, $mustContainAnyOf)) {
            return false;
        }

        if ($this->containsAnyOf($password, $cannotContainAnyOf)) {
            return false;
        }

        if (! $this->hasTwoOverlappingPairs($password)) {
            return false;
        }

        return true;
    }

    protected function containsAnyOf($password, Collection $set)
    {
        return $set->contains(function ($substring) use ($password) {
            return str_contains($password, $substring);
        });
    }

    protected function hasTwoOverlappingPairs($password)
    {
        preg_match('/([a-z])\1/', $password, $firstMatch, PREG_OFFSET_CAPTURE);
        preg_match('/([a-z])\1/', strrev($password), $lastMatch, PREG_OFFSET_CAPTURE);

        $offsetFirst = $firstMatch[0][1] ?? null;
        $offsetLast = $lastMatch[0][1] ?? null;

        if (is_null($offsetFirst) || is_null($offsetLast)) {
            return false;
        }

        return $offsetFirst + 1 < strlen($password) - $offsetLast - 2;
    }
}
