<?php

namespace App\Console\Commands\Year2020;

class Day11Puzzle2 extends Day11Puzzle1
{
    protected int $part = 2;

    protected function getNewSeatValue($x, $y, $current, $length, $height)
    {
        $seat = $current[$y][$x];

        $countVisibleOccupiedSeats = 0;

        // Left up
        $tmpX = $x - 1;
        $tmpY = $y - 1;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpY--;
            $tmpX--;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        // Up
        $tmpX = $x;
        $tmpY = $y - 1;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpY--;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        // Right up
        $tmpX = $x + 1;
        $tmpY = $y - 1;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpY--;
            $tmpX++;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        // Left
        $tmpX = $x - 1;
        $tmpY = $y;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpX--;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        // Right
        $tmpX = $x + 1;
        $tmpY = $y;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpX++;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        // Left down
        $tmpX = $x - 1;
        $tmpY = $y + 1;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpY++;
            $tmpX--;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        // Down
        $tmpX = $x;
        $tmpY = $y + 1;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpY++;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        // Right down
        $tmpX = $x + 1;
        $tmpY = $y + 1;
        while (($current[$tmpY][$tmpX] ?? null) == static::FLOOR) {
            $tmpY++;
            $tmpX++;
        }
        $countVisibleOccupiedSeats += (($current[$tmpY][$tmpX] ?? null) == static::OCCUPIED);

        if ($seat == static::EMPTY && $countVisibleOccupiedSeats == 0) {
            $seat = static::OCCUPIED;
        } else if ($seat == static::OCCUPIED && $countVisibleOccupiedSeats >= 5) {
            $seat = static::EMPTY;
        }

        return $seat;
    }
}
