<?php

namespace App\Console\Commands\Year2020;

class Day18Puzzle2 extends Day18Puzzle1
{
    protected int $part = 2;

    protected function parseLine(string $line)
    {
        // Resolve all sub-expressions (with brackets)
        while (preg_match('/\(([\d\s+*]*?)\)/', $line, $matches, PREG_OFFSET_CAPTURE)) {
            $subExpression = $matches[1][0];
            $position = $matches[0][1];
            $length = strlen($matches[0][0]);

            $line =
                substr($line, 0, $position) .
                $this->parseLine($subExpression) .
                substr($line, $position + $length);
        }

        // Resolve all + expressions, starting from left to right
        while (preg_match('/(\d+)\s[+]\s(\d+)/', $line, $matches, PREG_OFFSET_CAPTURE)) {
            $subExpression = $matches[0][0];
            $position = $matches[0][1];
            $length = strlen($subExpression);

            $line =
                substr($line, 0, $position) .
                eval("return {$subExpression};") .
                substr($line, $position + $length);
        }

        // Resolve all * expressions, starting from left to right
        while (preg_match('/(\d+)\s[*]\s(\d+)/', $line, $matches, PREG_OFFSET_CAPTURE)) {
            $subExpression = $matches[0][0];
            $position = $matches[0][1];
            $length = strlen($subExpression);

            $line =
                substr($line, 0, $position) .
                eval("return {$subExpression};") .
                substr($line, $position + $length);
        }

        return (int) $line;
    }
}
