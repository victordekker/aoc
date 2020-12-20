<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day16Puzzle2 extends Day16Puzzle1
{
    protected int $part = 2;

    const TARGET_WORD = 'departure';

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

        $myTicket = $this->parseTickets($myTicket)->first();

        $nearbyTickets = $this->parseTickets($nearbyTickets)
            ->filter(function ($ticket) use ($rules) {
                return ! $ticket->contains(function ($number) use ($rules) {
                    return ! $this->validInAnyRule($number, $rules);
                });
            });

        $order = $this->figureOutOrder($nearbyTickets, $rules)
            ->sortKeys();

        $this->answer = $order
            ->filter(function ($name) {
                return strpos($name, static::TARGET_WORD) === 0;
            })
            ->mapWithKeys(function ($name, $position) use ($myTicket) {
                return [$name => $myTicket[$position]];
            })
            ->values()
            ->reduce(function ($carry, $item) {
                return $carry * $item;
            }, 1);
    }

    protected function figureOutOrder(Collection $nearbyTickets, Collection $rules)
    {
        $order = collect();

        $ticketLength = $nearbyTickets->first()->count();

        while ($rules->isNotEmpty()) {
            for ($i = 0; $i < $ticketLength; $i++) {
                if ($order->has($i)) {
                    continue;
                }

                $ticketNumbers = $nearbyTickets->pluck($i);

                $possibleValidRules = $rules->filter(function (Collection $rule) use ($ticketNumbers) {
                    return $ticketNumbers->every(function ($number) use ($rule) {
                        return $this->validInRule($number, $rule);
                    });
                });

                if ($possibleValidRules->count() === 1) {
                    $foundRule = $possibleValidRules->keys()->first();
                    $order[$i] = $foundRule;
                    $rules = $rules->except($foundRule);
                }
            }
        }

        return $order;
    }
}
