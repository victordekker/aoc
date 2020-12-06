<?php

namespace App\Console\Commands\Year2015;

class Day6Puzzle2 extends Day6Puzzle1
{
    protected int $part = 2;

    const INSTRUCTION_TURN_ON = 'turn on';
    const INSTRUCTION_TURN_OFF = 'turn off';
    const INSTRUCTION_TOGGLE = 'toggle';

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $lightsTurnedOn = [];

        $this->explodePerLine($data)

            // Parse instructions
            ->map(function (string $line) {
                return $this->parseInstruction($line);
            })

            // Perform instructions
            ->each(function ($instruction) use (&$lightsTurnedOn) {
                for ($x = $instruction['minX']; $x <= $instruction['maxX']; $x++) {
                    for ($y = $instruction['minY']; $y <= $instruction['maxY']; $y++) {
                        $coordinate = $x . 'x' . $y;

                        // Note: lights are turned off by default
                        $result = $this->doAction($instruction['action'], $lightsTurnedOn[$coordinate] ?? 0);
                        if ($result) {
                            $lightsTurnedOn[$coordinate] = $result;
                        } else {
                            unset($lightsTurnedOn[$coordinate]);
                        }
                    }
                }
            });

        $this->answer = array_sum($lightsTurnedOn);
    }

    protected function doAction($action, $state)
    {
        switch ($action) {
            case static::INSTRUCTION_TURN_ON:
                return $state + 1;
            case static::INSTRUCTION_TURN_OFF:
                return $state ? $state - 1 : 0;
            case static::INSTRUCTION_TOGGLE:
                return $state + 2;
        }

        return $state;
    }
}
