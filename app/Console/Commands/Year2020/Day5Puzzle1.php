<?php

namespace App\Console\Commands\Year2020;

class Day5Puzzle1 extends Year2020
{
    protected int $day = 5;
    protected int $part = 1;

    protected function solve(string $data)
    {
        $this->answer = $this->explodePerLine($data)
            ->map(function (string $line) {
                return $this->parseLine($line);
            })
            ->pluck('id')
            ->max();
    }

    protected function parseLine(string $line)
    {
        $row = $this->runThroughBinaryTree(str_split(substr($line, 0, 7)), 'F');
        $column = $this->runThroughBinaryTree(str_split(substr($line, 7, 3)), 'L');

        return [
            'row' => $row,
            'column' => $column,
            'id' => $row * 8 + $column,
        ];
    }

    protected function runThroughBinaryTree(array $instructions, $takeFirst)
    {
        $range = pow(2, count($instructions));
        $letter = array_shift($instructions);

        $add = $letter == $takeFirst ? 0 : ($range / 2);

        if (empty($instructions)) {
            return $add;
        }

        return $add + $this->runThroughBinaryTree($instructions, $takeFirst);
    }
}
