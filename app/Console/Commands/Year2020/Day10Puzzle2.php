<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day10Puzzle2 extends Day10Puzzle1
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
            ->map(function ($line) {
                return (int) $line; // Cast to integer, just to make sure
            })
            ->sort()
            ->values();

        $data->push($data->last() + 3); // Add the last adapter

        $this->answer = $this->splitInSections($data)
            ->map(function ($section) {
                return $this->mapSectionToNumber($section);
            })
            ->reduce(function ($carry, $number) {
                return $carry * $number;
            }, 1);
    }

    protected function splitInSections(Collection $data)
    {
        $sections = collect();
        $currentSectionStart = 0;
        $length = 1;

        foreach ($data as $key => $item) {
            $diff = $item - ($data[$key - 1] ?? 0);

            // No need to handle case "$diff == 2" because this does not occur in input data
            if ($diff == 3) {
                $sections->push($data->slice($currentSectionStart, $length));
                $currentSectionStart = $key;
                $length = 1;
            } else {
                $length++;
            }
        }

        return $sections;
    }

    protected function mapSectionToNumber($section)
    {
        switch (count($section)) {
            // No need for higher cases than 5, since input data has sections of at most 5.
            case 5:
                return 7;
            case 4:
                return 4;
            case 3:
                return 2;
            case 2:
            case 1:
                return 1;
        }

        return 0;
    }
}
