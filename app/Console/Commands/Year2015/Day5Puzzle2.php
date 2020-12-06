<?php

namespace App\Console\Commands\Year2015;

class Day5Puzzle2 extends Day5Puzzle1
{
    protected int $part = 2;

    protected function isNice(string $string)
    {
        return $this->containsTwoDuplicatePairs($string) &&
            $this->containsRepeatingLetterWithLetterInBetween($string);
    }

    protected function containsTwoDuplicatePairs(string $string)
    {
        return
            $this->checkForNonOverlappingDuplicatePairs(str_split($string, 2), $string) ||
            $this->checkForNonOverlappingDuplicatePairs(
                str_split(substr($string, 1), 2),
                substr($string, 1)
            );
    }

    protected function checkForNonOverlappingDuplicatePairs(array $pairs, $string)
    {
        return collect($pairs)->contains(function ($pair, $i) use ($string) {
            $offset = ($i + 1) * 2;
            return $offset < strlen($string) && (strpos($string, $pair, $offset) !== false);
        });
    }

    protected function containsRepeatingLetterWithLetterInBetween(string $string)
    {
        $string = str_split($string);
        return collect($string)->contains(function ($letter, $i) use ($string) {
            return $letter === ($string[$i + 2] ?? null);
        });
    }
}
