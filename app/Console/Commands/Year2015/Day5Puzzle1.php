<?php

namespace App\Console\Commands\Year2015;

class Day5Puzzle1 extends Year2015
{
    protected int $day = 5;
    protected int $part = 1;

    const VOWELS = ['a', 'e', 'i', 'o', 'u'];
    const MIN_VOWELS = 3;
    const FORBIDDEN = ['ab', 'cd', 'pq', 'xy'];

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
            ->filter(function ($line) {
                return $this->isNice($line);
            })
            ->count();
    }

    protected function isNice(string $string)
    {
        return $this->hasAtLeast3Vowels($string) &&
            $this->containsLettersTwiceInARow($string) &&
            ! $this->containsForbiddenString($string);
    }

    protected function hasAtLeast3Vowels(string $string)
    {
        return collect(static::VOWELS)->sum(function ($vowel) use ($string) {
            return substr_count($string, $vowel);
        }) >= static::MIN_VOWELS;
    }

    protected function containsLettersTwiceInARow(string $string)
    {
        $string = str_split($string);
        return collect($string)->contains(function ($letter, $i) use ($string) {
            return $letter === ($string[$i + 1] ?? null);
        });
    }

    protected function containsForbiddenString(string $string)
    {
        return collect(static::FORBIDDEN)->contains(function ($forbidden) use ($string) {
            return str_contains($string, $forbidden);
        });
    }
}
