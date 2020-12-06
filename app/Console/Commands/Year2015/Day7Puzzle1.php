<?php

namespace App\Console\Commands\Year2015;

class Day7Puzzle1 extends Year2015
{
    protected int $day = 7;
    protected int $part = 1;

    const OPERATION_BITWISE_NOT = 'NOT '; // 1 input: NOT x -> h
    const OPERATION_BITWISE_AND = ' AND ';
    const OPERATION_BITWISE_OR = ' OR ';
    const OPERATION_BITWISE_LSHIFT = ' LSHIFT ';
    const OPERATION_BITWISE_RSHIFT = ' RSHIFT ';

    const TARGET = 'a';

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

        $this->answer = $this->runThroughOnce([], $instructions, static::TARGET);
    }

    protected function parseInstructions(string $line)
    {
        list($fullInput, $output) = explode(' -> ', $line, 2);

        $operationsToCheck = [
            static::OPERATION_BITWISE_NOT,
            static::OPERATION_BITWISE_AND,
            static::OPERATION_BITWISE_OR,
            static::OPERATION_BITWISE_LSHIFT,
            static::OPERATION_BITWISE_RSHIFT,
        ];

        foreach ($operationsToCheck as $operationToCheck) {
            if (str_contains($fullInput, $operationToCheck)) {
                $operation = $operationToCheck;
                break;
            }
        }

        // Get all inputs, remove empty strings (but not 0 values!)
        $inputs = array_filter(explode($operationToCheck, $fullInput), function ($value) {
            return $value !== '';
        });

        $dependencies = array_filter($inputs, function ($value) {
            return $this->isWire($value);
        });

        return [
            'output' => $output,
            'operation' => $operation ?? null,
            'inputs' => array_values($inputs),
            'dependencies' => $dependencies,
        ];
    }

    protected function runThroughOnce($wires, $instructions, $targetWire)
    {
        while ($instructions->isNotEmpty()) {
            list ($instructionsToPerform, $instructions) = $instructions->partition(function ($instruction) {
                return empty($instruction['dependencies']);
            });

            if ($instructionsToPerform->isEmpty()) {
                break;
            }

            // Perform instructions that we can
            $instructionsToPerform->each(function ($instruction) use (&$wires) {
                $input = collect($instruction['inputs'])->map(function ($input) use ($wires) {
                    return $this->isWire($input)
                        ? $wires[$input]
                        : (int) $input;
                });

                $wires[$instruction['output']] = $this->performOperation($instruction['operation'], $input);
            });

            // Update dependencies
            $instructions = $instructions->map(function ($instruction) use ($wires) {
                $instruction['dependencies'] = array_filter($instruction['dependencies'], function ($dependency) use ($wires) {
                    return ! isset($wires[$dependency]);
                });

                return $instruction;
            });
        }

        return $wires[$targetWire];
    }

    protected function performOperation($operation, $inputs)
    {
        switch ($operation) {
            case static::OPERATION_BITWISE_NOT:
                return ~ $inputs[0];
            case static::OPERATION_BITWISE_AND:
                return $inputs[0] & $inputs[1];
            case static::OPERATION_BITWISE_OR:
                return $inputs[0] | $inputs[1];
            case static::OPERATION_BITWISE_LSHIFT:
                return $inputs[0] << $inputs[1];
            case static::OPERATION_BITWISE_RSHIFT:
                return $inputs[0] >> $inputs[1];
        }

        // Default is a "set" operation
        return $inputs[0];
    }

    protected function isWire($value)
    {
        // Check if the set input is a wire or a number: cast input to int, then back to string => does it match the original input? Then it was a number!
        return ($value != (string) (int) $value);
    }
}
