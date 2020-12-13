<?php

namespace App\Console\Commands\Year2015;

use Illuminate\Support\Collection;

class Day13Puzzle1 extends Year2015
{
    protected int $day = 13;
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
        $happinessMapping = $this->getHappinessMapping($this->explodePerLine($data));

        $participants = $this->getParticipants($happinessMapping);

        $possibleOrderings = $this->getPossibleOrderings($participants->first(), $participants);

        $this->answer = $possibleOrderings
            ->mapWithKeys(function ($ordering) use ($happinessMapping) {
                return [$ordering => $this->calculateHappiness($ordering, $happinessMapping)];
            })
            ->sort()
            ->last();
    }

    protected function getHappinessMapping(Collection $data)
    {
        return $data ->mapWithKeys(function ($line) {
            return $this->parseHappiness($line);
        });
    }

    protected function getParticipants(Collection $happinessMapping)
    {
        return $happinessMapping
            ->keys()
            ->map(function ($key) {
                return explode('+', $key, 2)[0];
            })
            ->unique()
            ->values();
    }

    protected function parseHappiness(string $line)
    {
        preg_match('/(\w+) would (lose|gain) (\d+) happiness units by sitting next to (\w+)\./', $line, $matches);
        $subject = $matches[1];
        $target = $matches[4];
        $value = ($matches[2] === 'gain')
            ? (int) $matches[3]
            : ((int) $matches[3]) * -1;

        return [$subject . '+' . $target => $value];
    }

    protected function getPossibleOrderings(string $firstParticipant, Collection $otherParticipants)
    {
        $otherParticipants = $otherParticipants->diff([$firstParticipant]);

        if ($otherParticipants->isEmpty()) {
            return collect($firstParticipant);
        }

        return $otherParticipants
            ->flatMap(function ($participant) use ($otherParticipants) {
                return $this->getPossibleOrderings($participant, $otherParticipants);
            })
            ->map(function ($ordering) use ($firstParticipant) {
                return $firstParticipant . '+' . $ordering;
            });
    }

    protected function calculateHappiness(string $ordering, Collection $happinessMapping)
    {
        $ordering = explode('+', $ordering);
        $happiness = 0;
        $countParticipants = count($ordering);

        for ($i = 0; $i < $countParticipants; $i++) {
            $participant = $ordering[$i];
            $left = ($i > 0) ? $ordering[$i - 1] : $ordering[$countParticipants - 1];
            $right = ($i < $countParticipants - 1) ? $ordering[$i + 1] : $ordering[0];

            // Add happiness for one to the left
            $happiness += $happinessMapping[$participant . '+' . $left];

            // Add happiness for one tot he right
            $happiness += $happinessMapping[$participant . '+' . $right];
        }

        return $happiness;
    }
}
