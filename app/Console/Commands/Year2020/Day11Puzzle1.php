<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day11Puzzle1 extends Year2020
{
    protected int $day = 11;
    protected int $part = 1;

    const FLOOR = '.';
    const EMPTY = 'L';
    const OCCUPIED = '#';

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $currentGeneration = $this->explodePerLine($data)->map(function ($line) {
            return str_split($line);
        })->toArray();

        $serializedCurrent = 'a';
        $serializedNext = 'b';

        while ($serializedCurrent !== $serializedNext) {
            $nextGeneration = $this->runOneGeneration($currentGeneration);

            $serializedCurrent = serialize($currentGeneration);
            $serializedNext = serialize($nextGeneration);

            $currentGeneration = $nextGeneration;
        }

        $this->answer = $this->countOccupiedSeats(collect($currentGeneration));
    }

    protected function runOneGeneration(array $current)
    {
        $height = count($current);
        $length = count($current[0]);

        $new = Collection::times($height, function ($number) {
            return [];
        })->toArray();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $length; $x++) {
                $new[$y][] = $this->getNewSeatValue($x, $y, $current, $length, $height);
            }
        }

        return $new;
    }

    protected function getNewSeatValue($x, $y, $current, $length, $height)
    {
        $seat = $current[$y][$x];
        $countAdjacentOccupiedSeats = 0;

        if ($y - 1 >= 0) {
            $countAdjacentOccupiedSeats +=
                (($x - 1 >= 0) ? ($current[$y - 1][$x - 1] == static::OCCUPIED) : 0) +
                ($current[$y - 1][$x] == static::OCCUPIED) +
                (($x + 1 < $length) ? ($current[$y - 1][$x + 1] == static::OCCUPIED) : 0);
        }

        $countAdjacentOccupiedSeats +=
            (($x - 1 >= 0) ? ($current[$y][$x - 1] == static::OCCUPIED) : 0) +
            (($x + 1 < $length) ? ($current[$y][$x + 1] == static::OCCUPIED) : 0);

        if (($y + 1) < $height) {
            $countAdjacentOccupiedSeats +=
                (($x - 1 >= 0) ? ($current[$y + 1][$x - 1] == static::OCCUPIED) : 0) +
                ($current[$y + 1][$x] == static::OCCUPIED) +
                (($x + 1 < $length) ? ($current[$y + 1][$x + 1] == static::OCCUPIED) : 0);
        }

        if ($seat == static::EMPTY && $countAdjacentOccupiedSeats == 0) {
            $seat = static::OCCUPIED;
        } else if ($seat == static::OCCUPIED && $countAdjacentOccupiedSeats >= 4) {
            $seat = static::EMPTY;
        }

        return $seat;
    }

    protected function countOccupiedSeats(Collection $generation)
    {
        return $generation->flatten()
            ->filter(function ($seat) {
                return $seat == static::OCCUPIED;
            })
            ->count();
    }
}
