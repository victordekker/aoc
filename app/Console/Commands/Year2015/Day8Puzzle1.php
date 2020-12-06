<?php

namespace App\Console\Commands\Year2015;

class Day8Puzzle1 extends Year2015
{
    protected int $day = 8;
    protected int $part = 1;

    const QUOTE = '"';
    const NORMAL_ESCAPES = ['\\\\', '\\"'];
    const HEX_ESCAPES_SIGNALER = '\\x';
    const PLACEHOLDER = 'a'; // Must be a normal character!

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $this->answer = $this->explodePerLine($data)
            ->map(function (string $line) {
                return $this->getNumberOfStringCodeCharacters($line) - $this->getNumberOfInMemoryCharacters($line);
            })
            ->sum();
    }

    protected function getNumberOfStringCodeCharacters(string $input)
    {
        return strlen($input);
    }

    protected function getNumberOfInMemoryCharacters(string $input)
    {
        // Strip first quote if necessary
        $input = strpos($input, static::QUOTE) === 0
            ? substr($input, 1)
            : $input;

        // Strip last quote if necessary
        $lastPosition = strlen($input) - 1;
        $input = strpos($input, static::QUOTE, $lastPosition) === $lastPosition
            ? substr($input, 0, $lastPosition)
            : $input;

        $input = str_replace(static::NORMAL_ESCAPES, static::PLACEHOLDER, $input);

        $input = preg_replace('/' . preg_quote(static::HEX_ESCAPES_SIGNALER) . '[a-zA-Z0-9]{2}/', static::PLACEHOLDER, $input);

        return strlen($input);
    }
}
