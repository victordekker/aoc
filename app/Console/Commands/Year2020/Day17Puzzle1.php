<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day17Puzzle1 extends Year2020
{
    protected int $day = 17;
    protected int $part = 1;

    const CYCLE_COUNT = 6;
    const ACTIVE = '#';
    const INACTIVE = '.';

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
        
        $this->answer = collect($generation)
            ->flatten()
            ->filter(function ($cell) {
                return $cell === static::ACTIVE;
            })
            ->count();
    }

    protected function loadStartingGeneration(Collection $lines)
    {
        $height = $lines->count();
        if ($height === 0) {
            throw new \Exception("Empty input data given!");
        }

        // We assume input is one layer on z=0.
        $generation = [0 => []];

        for ($i = 0; $i < $height; $i++) {
            $generation[0][$i] = str_split($lines[$i]);
        }

        return $generation;
    }

    protected function runOneGeneration(array $generation)
    {
        $layers = count($generation) + 2; // +2 to add expansion in each direction
        $height = count($generation[0]) + 2;
        $width = count($generation[0][0]) + 2;

        $newGeneration = [];

        for ($z = 0; $z < $layers; $z++) {
            $newGeneration[$z] = [];

            for ($y = 0; $y < $height; $y++) {
                $newGeneration[$z][$y] = [];

                for ($x = 0; $x < $width; $x++) {
                    // -1 in all coordinates from the old generation to compensate for the expansion in each direction
                    $active = $generation[$z - 1][$y - 1][$x - 1] ?? static::INACTIVE;

                    // Get amount of active surrounding cells.
                    $surroundingActiveCount =
                        (($generation[$z - 2][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 2][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z - 1][$y][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y - 2][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y - 2][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y - 2][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y - 1][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y - 1][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y - 1][$x] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y][$x - 2] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y][$x - 1] ?? static::INACTIVE) === static::ACTIVE) +
                        (($generation[$z][$y][$x] ?? static::INACTIVE) === static::ACTIVE);

                    if ($active === static::ACTIVE && ($surroundingActiveCount < 2 || $surroundingActiveCount > 3)) {
                        $active = static::INACTIVE;
                    } elseif ($active === static::INACTIVE && $surroundingActiveCount === 3) {
                        $active = static::ACTIVE;
                    }

                    $newGeneration[$z][$y][$x] = $active;
                }
            }
        }

        return $newGeneration;
    }

    protected function displayGeneration(array $generation)
    {
        foreach ($generation as $z => $layer) {
            $this->output->writeln("z={$z}");
            foreach ($layer as $line) {
                $this->output->writeln(implode('', $line));
            }
            $this->output->writeln('');
        }
    }
}
