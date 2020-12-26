<?php

namespace App\Console\Commands\Year2020;

class Day18Puzzle1 extends Year2020
{
    protected int $day = 18;
    protected int $part = 1;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     * @throws \Exception
     */
    protected function solve(string $data)
    {
        $this->answer = $this->explodePerLine($data)
            ->map(function (string $line) {
                return $this->parseLine($line);
            })
            ->sum();
    }

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

        // Resolve all + and * expressions, starting from left to right
        while (preg_match('/(\d+)\s[+*]\s(\d+)/', $line, $matches, PREG_OFFSET_CAPTURE)) {
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
