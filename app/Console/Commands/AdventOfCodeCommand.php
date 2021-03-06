<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

abstract class AdventOfCodeCommand extends Command
{
    protected int $year;

    protected int $day;

    protected int $part;

    protected bool $inputIsFilename = true;

    protected $answer = null;

    public function __construct()
    {
        if (empty($this->signature)) {
            $this->signature = "aoc{$this->year}:d{$this->day}p{$this->part} " .
                ($this->inputIsFilename ? '{file : input file}' : '{input : input string}');
        }

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setInputOptions();

        $this->solve($this->getData());

        if (! is_null($this->answer)) {
            $this->output->success((string) $this->answer);
        } else {
            $this->output->error("Unsolved");
        }
    }

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    abstract protected function solve(string $data);

    protected function setInputOptions()
    {
        // Do nothing.
    }

    /**
     * @return string
     */
    protected function getData()
    {
        return $this->inputIsFilename
            ? File::get($this->input->getArgument('file'))
            : $this->input->getArgument('input');
    }

    protected function explodePerLine(string $data)
    {
        return collect(explode("\n", $data))

            // Make sure we do not count empty lines
            ->filter(function ($line) {
                return $line !== '';
            });
    }

    protected function explodePerEmptyLine(string $data)
    {
        return collect(explode("\n\n", $data))

            // Make sure we do not count empty lines
            ->filter(function ($lines) {
                return $lines !== '';
            });
    }
}
