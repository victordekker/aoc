<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day8Puzzle2 extends Day8Puzzle1
{
    protected int $part = 2;

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

        $newInstructions = $instructions;
        $previousChangeId = null;
        list ($hasLoop, $accumulator) = $this->runThrough($newInstructions);

        while ($hasLoop) {
            list($newInstructions, $previousChangeId) = $this->changeInstructions(clone $instructions, $previousChangeId);
            list ($hasLoop, $accumulator) = $this->runThrough($newInstructions);
        }

        $this->answer = $accumulator;
    }

    protected function changeInstructions(Collection $instructions, $previousChangeId)
    {
        $changeId = $instructions->search(function ($instruction, $id) use ($previousChangeId) {
            return (is_null($previousChangeId) || $previousChangeId < $id) &&
                in_array(explode(' ', $instruction, 2)[0], ['nop', 'jmp']);
        });

        $oldInstruction = $instructions[$changeId];
        $instructions[$changeId] = str_replace(['nop', 'jmp'], ['jmp', 'nop'], $oldInstruction);

        return [$instructions, $changeId];
    }
}
