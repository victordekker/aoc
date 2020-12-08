<?php

namespace App\Console\Commands\Year2020;

class Day8Puzzle1 extends Year2020
{
    protected int $day = 8;
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
        $instructions = $this->explodePerLine($data);

        list ($hasLoop, $accumulator) = $this->runThrough($instructions);

        $this->answer = $accumulator;
    }

    protected function runThrough($instructions)
    {
        $executedInstructions = [];

        $accumulator = 0;
        $i = 0;

        while ($i < $instructions->count()) {
            if (! empty($executedInstructions[$i])) {
                return [true, $accumulator];
            }

            $executedInstructions[$i] = true;

            list ($instruction, $argument) = explode(' ', $instructions[$i], 2);

            list ($i, $accumulator) = $this->executeInstruction($instruction, (int) $argument, $i, $accumulator);
        }

        return [false, $accumulator];
    }

    protected function executeInstruction($instruction, $argument, $i, $accumulator)
    {
        switch ($instruction) {
            case 'acc':
                return [$i + 1, $accumulator + $argument];
            case 'jmp':
                return [$i + $argument, $accumulator];
            default:
                return [$i + 1, $accumulator];
        }
    }
}
