<?php

namespace App\Console\Commands\Year2015;

class Day7Puzzle2 extends Day7Puzzle1
{
    protected int $part = 2;

    const OVERRIDE_TARGET = 'b';

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $instructions = $this->explodePerLine($data)->map(function (string $line) {
            return $this->parseInstructions($line);
        });

        $override = $this->runThroughOnce([], $instructions, static::TARGET);

        // Filter the set operation of the override target
        $instructions = $instructions->reject(function ($instruction) {
            return is_null($instruction['operation']) && // Set operation
                $instruction['output'] == static::OVERRIDE_TARGET;
        });

        $this->answer = $this->runThroughOnce([static::OVERRIDE_TARGET => $override], $instructions, static::TARGET);
    }
}
