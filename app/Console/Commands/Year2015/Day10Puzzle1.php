<?php

namespace App\Console\Commands\Year2015;

use Illuminate\Support\Collection;

class Day10Puzzle1 extends Year2015
{
    protected int $day = 10;
    protected int $part = 1;
    protected bool $inputIsFilename = false;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $this->answer = strlen($this->runThrough(40, $data));
    }

    protected function runThrough($times, $startText)
    {
        return Collection::times($times)->reduce(function ($text, $i) {
            $this->output->writeln($i . ': ' . strlen($text));
            return $this->lookAndSay($text);
        }, $startText);
    }

    protected function lookAndSay($text)
    {
        $newText = '';

        while ($text !== '') {
            $group = $this->getGroupOfRepeatingFirstCharacters($text);

            $amount = strlen($group);
            $number = substr($group, 0, 1);
            $newText .= $amount . $number;

            $text = substr($text, $amount);
        }

        return $newText;
    }

    protected function getGroupOfRepeatingFirstCharacters($text)
    {
        // Match the group of repeating 1st character of the $text and put it in $match[0]
        preg_match('/^([0-9])\1*/', $text, $match);

        return $match[0];
    }

//    protected function getGroupOfRepeatingFirstCharacters($text)
//    {
//        $character = substr($text, 0, 1);
//        $i = 0;
//        $length = strlen($text);
//
//        while ($i < $length && $character == substr($text, $i, 1)) {
//            $i++;
//        }
//
//        return str_repeat($character, $i);
//    }
}
