<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day17Puzzle2 extends Day17Puzzle1
{
    protected int $part = 2;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     * @throws \Exception
     */
    protected function solve(string $data)
    {
        $generation = $this->loadStartingGeneration($this->explodePerLine($data));

        $this->info('Initial generation');
        $this->displayGeneration($generation);
        
        for ($i = 0; $i < static::CYCLE_COUNT; $i++) {
            $generation = $this->runOneGeneration($generation);

            $this->info('Generation ' . ($i + 1));
            $this->displayGeneration($generation);
        }

        // Change how the answer is calculated, since simply flattening directly will result in memory-limit errors.
        $activeCount = 0;
        foreach ($generation as $layers) {
            foreach ($layers as $lines) {
                foreach ($lines as $cells) {
                    foreach ($cells as $cell) {
                        $activeCount += ($cell === static::ACTIVE);
                    }
                }
            }
        }

        $this->answer = $activeCount;
    }

    protected function loadStartingGeneration(Collection $lines)
    {
        $height = $lines->count();
        if ($height === 0) {
            throw new \Exception("Empty input data given!");
        }

        // We assume input is one layer on w=0, z=0.
        $generation = [0 => [0 => []]];

        for ($i = 0; $i < $height; $i++) {
            $generation[0][0][$i] = str_split($lines[$i]);
        }

        return $generation;
    }

    protected function runOneGeneration(array $generation)
    {
        $hyperLayers = count($generation) + 2; // +2 to add expansion in each direction
        $layers = count($generation[0]) + 2;
        $height = count($generation[0][0]) + 2;
        $width = count($generation[0][0][0]) + 2;

        $newGeneration = [];

        for ($w = 0; $w < $hyperLayers; $w++) {
            $newGeneration[$w] = [];

            for ($z = 0; $z < $layers; $z++) {
                $newGeneration[$w][$z] = [];

                for ($y = 0; $y < $height; $y++) {
                    $newGeneration[$w][$z][$y] = [];

                    for ($x = 0; $x < $width; $x++) {
                        // -1 in all coordinates from the old generation to compensate for the expansion in each direction
                        $active = $generation[$w - 1][$z - 1][$y - 1][$x - 1] ?? static::INACTIVE;

                        // Get amount of active surrounding cells.
                        $surroundingActiveCount =
                            (($generation[$w - 2][$z - 2][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 2][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z - 1][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 2][$z][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +

                            (($generation[$w - 1][$z - 2][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 2][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z - 1][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w - 1][$z][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +

                            (($generation[$w][$z - 2][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 2][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z - 1][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                            (($generation[$w][$z][$y][$x] ?? static::INACTIVE) === static::ACTIVE);

                        if ($active === static::ACTIVE && ($surroundingActiveCount < 2 || $surroundingActiveCount > 3)) {
                            $active = static::INACTIVE;
                        } elseif ($active === static::INACTIVE && $surroundingActiveCount === 3) {
                            $active = static::ACTIVE;
                        }

                        $newGeneration[$w][$z][$y][$x] = $active;
                    }
                }
            }
        }

        return $newGeneration;
    }

    protected function displayGeneration(array $generation)
    {
        // Let's just not try to display this...
    }
}
