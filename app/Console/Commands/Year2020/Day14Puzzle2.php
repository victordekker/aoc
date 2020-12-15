<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day14Puzzle2 extends Day14Puzzle1
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
        $data = $this->explodePerLine($data)
            ->map(function (string $line) {
                return $this->parseData($line);
            });

        $currentMask = str_repeat('X', 36);
        $memory = [];

        foreach ($data as $line) {
            if ($line['target'] == 'mask') {
                $currentMask = $line['value'];
                continue;
            }

            $targets = $this->applyFloatingMask($line['target'], $currentMask);

            foreach ($targets as $target) {
                $memory[$target] = $line['value'];
            }
        }

        $this->answer = array_sum($memory);
    }

    protected function applyFloatingMask(int $value, string $mask)
    {
        $maskLength = strlen($mask);

        preg_match_all('/1/', $mask, $matches, PREG_OFFSET_CAPTURE);

        $number = collect($matches[0])
            ->map(function ($match) use ($maskLength) {
                return $maskLength - ((int) $match[1]) - 1;
            })
            ->values()
            ->map(function ($number) {
                return pow(2, $number);
            })
            ->sum();

        // Make sure that the bits in $number are set in $value
        $value = $value | $number;

        preg_match_all('/X/', $mask, $matches, PREG_OFFSET_CAPTURE);
        $numbers = collect($matches[0])
            ->map(function ($match) use ($maskLength) {
                return $maskLength - ((int) $match[1]) - 1;
            })
            ->values();

        return $this->makePossibleNumbers($numbers)
            ->map(function ($number) use ($value) {
                $allZeros = $number[0];
                $allOnes = $number[1];

                $newValue = $value & (~ $allZeros);
                return $newValue | $allOnes;
            });
    }

    protected function makePossibleNumbers(Collection $numbers)
    {
        if ($numbers->isEmpty()) {
            return collect([
                [0, 0],
            ]);
        }

        $firstNumber = $numbers->shift();

        return $this->makePossibleNumbers($numbers)->flatMap(function ($number) use ($firstNumber) {
            $allZeros = $number[0];
            $allOnes = $number[1];

            $firstNumberValue = pow(2, $firstNumber);

            return [
                [$allZeros + $firstNumberValue, $allOnes],
                [$allZeros, $allOnes + $firstNumberValue],
            ];
        });
    }
}
