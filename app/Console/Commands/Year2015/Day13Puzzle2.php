<?php

namespace App\Console\Commands\Year2015;

use Illuminate\Support\Collection;

class Day13Puzzle2 extends Day13Puzzle1
{
    protected int $part = 2;

    protected function getHappinessMapping(Collection $data)
    {
        $happinessMapping = parent::getHappinessMapping($data);

        $participants = $this->getParticipants($happinessMapping);

        $myHappiness = $participants->flatMap(function ($participant) {
            return [
                $participant . '+me' => 0,
                'me+' . $participant => 0,
            ];
        });

        return $happinessMapping->merge($myHappiness);
    }
}
