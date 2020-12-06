<?php

namespace App\Console\Commands\Year2015;

class Day8Puzzle2 extends Day8Puzzle1
{
    protected int $part = 2;

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
                return $this->getNumberOfEncodedCharacters($line) - $this->getNumberOfStringCodeCharacters($line);
            })
            ->sum();
    }

    protected function getNumberOfStringCodeCharacters(string $input)
    {
        return strlen($input);
    }

    protected function getNumberOfEncodedCharacters(string $input)
    {
        $mapping = [
            '\\' => '\\\\',
            static::QUOTE => '\\' . static::QUOTE,
        ];

        $input = str_replace(array_keys($mapping), array_values($mapping), $input);

        $input = '"' . $input . '"';

        return strlen($input);
    }
}
