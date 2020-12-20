<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day16Puzzle1 extends Year2020
{
    protected int $day = 16;
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
        list ($rules, $myTicket, $nearbyTickets) = explode("\n\n", $data, 3);

        $rules = $this->parseRules($rules);
        $nearbyTickets = $this->parseTickets($nearbyTickets);

        $this->answer = $nearbyTickets
            ->flatten()
            ->filter(function ($number) use ($rules) {
                return ! $this->validInAnyRule($number, $rules);
            })
            ->sum();
    }

    protected function parseRules(string $rules)
    {
        return $this->explodePerLine($rules)
            ->mapWithKeys(function (string $rule) {
                list ($name, $ranges) = explode(': ', $rule, 2);

                return [$name => collect(explode(' or ', $ranges))->map(function (string $range) {
                    list ($min, $max) = explode('-', $range, 2);
                    return [(int) $min, (int) $max];
                })];
            });
    }

    protected function parseTickets(string $tickets)
    {
        $tickets = $this->explodePerLine($tickets);
        $tickets->shift(); // Remove first line

        return $tickets->map(function (string $ticket) {
            return collect(explode(',', $ticket))->map(function ($number) {
                return (int) $number;
            });
        });
    }

    protected function validInAnyRule($number, Collection $rules)
    {
        return $rules->contains(function (Collection $rule) use ($number) {
            return $this->validInRule($number, $rule);
        });
    }

    protected function validInRule($number, Collection $rule)
    {
        return $rule->contains(function ($range) use ($number) {
            return $range[0] <= $number && $range[1] >= $number;
        });
    }
}
