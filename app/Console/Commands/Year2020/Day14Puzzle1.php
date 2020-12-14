<?php

namespace App\Console\Commands\Year2020;

class Day14Puzzle1 extends Year2020
{
    protected int $day = 14;
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

            $memory[$line['target']] = $this->applyMask($line['value'], $currentMask);
        }

        $this->answer = array_sum($memory);
    }

    protected function parseData(string $line)
    {
        if (preg_match('/mask = ([01X]+)/', $line, $matches)) {
            return [
                'target' => 'mask',
                'value' => $matches[1],
            ];
        };

        preg_match('/mem\[(\d+)] = (\d*)/', $line, $matches);

        return [
            'target' => (int) $matches[1],
            'value' => (int) $matches[2],
        ];
    }

    protected function applyMask(int $value, string $mask)
    {
        $maskLength = strlen($mask);

        preg_match_all('/0/', $mask, $matches, PREG_OFFSET_CAPTURE);

        $number = collect($matches[0])
            ->map(function ($match) use ($maskLength) {
                return $maskLength - ((int) $match[1]) - 1;
            })
            ->values()
            ->map(function ($number) use ($maskLength) {
                return pow(2, $number);
            })
            ->sum();

        // Make sure that the bits in $number are not set in $value
        $value = $value & (~ $number);

        preg_match_all('/1/', $mask, $matches, PREG_OFFSET_CAPTURE);

        $number = collect($matches[0])
            ->map(function ($match) use ($maskLength) {
                return $maskLength - ((int) $match[1]) - 1;
            })
            ->values()
            ->map(function ($number) use ($maskLength) {
                return pow(2, $number);
            })
            ->sum();

        // Make sure that the bits in $number are set in $value
        return $value | $number;
    }
}
