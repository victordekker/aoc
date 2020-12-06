<?php

namespace App\Console\Commands\Year2015;

class Day6Puzzle1 extends Year2015
{
    protected int $day = 6;
    protected int $part = 1;

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
                        if ($this->doAction($instruction['action'], $lightsTurnedOn[$coordinate] ?? false)) {
                            $lightsTurnedOn[$coordinate] = true;
                        } else {
                            unset($lightsTurnedOn[$coordinate]);
                        }
                    }
                }
            });

        $this->answer = count($lightsTurnedOn);
    }

    protected function parseInstruction(string $line)
    {
        $action = $this->findAction($line);

        $lineWithoutAction = trim(str_replace($action, '', $line));
        $parts = explode(' through ', $lineWithoutAction);
        $min = explode(',', $parts[0]);
        $max = explode(',', $parts[1]);

        return [
            'action' => $action,
            'minX' => $min[0],
            'maxX' => $max[0],
            'minY' => $min[1],
            'maxY' => $max[1],
        ];
    }

    protected function findAction($line)
    {
        $actions = [
            static::INSTRUCTION_TURN_ON,
            static::INSTRUCTION_TURN_OFF,
            static::INSTRUCTION_TOGGLE,
        ];

        foreach ($actions as $action) {
            if (strpos($line, $action) === 0) {
                return $action;
            }
        }

        return null;
    }

    protected function doAction($action, $state)
    {
        switch ($action) {
            case static::INSTRUCTION_TURN_ON:
                return true;
            case static::INSTRUCTION_TURN_OFF:
                return false;
            case static::INSTRUCTION_TOGGLE:
                return ! $state;
        }

        return $state;
    }
}
