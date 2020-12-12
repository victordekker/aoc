<?php

namespace App\Console\Commands\Year2020;

use Illuminate\Support\Collection;

class Day12Puzzle1 extends Year2020
{
    protected int $day = 12;
    protected int $part = 1;

    const NORTH = 'N';
    const SOUTH = 'S';
    const EAST = 'E';
    const WEST = 'W';

    // Note: rotations in input are 90 / 180 / 270 degrees
    const ROTATE_LEFT = 'L';
    const ROTATE_RIGHT = 'R';
    const FORWARD = 'F';

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        list($compassActions, $directionalActions) = $this->explodePerLine($data)
            ->map(function ($line) {
                return [
                    'action' => substr($line, 0, 1),
                    'value' => (int) substr($line, 1),
                ];
            })
            ->partition(function ($action) {
                return $action['action'] == static::NORTH ||
                    $action['action'] == static::SOUTH ||
                    $action['action'] == static::EAST ||
                    $action['action'] == static::WEST;
            });
        
        $this->answer = abs(
            $this->getManhattanDistanceForCompassActions($compassActions) +
            $this->getManhattanDistanceForDirectionalActions($directionalActions, static::EAST)
        );
    }

    protected function getManhattanDistanceForCompassActions(Collection $actions)
    {
        return $actions
            ->map(function ($action) {
                return ($action['action'] == static::NORTH || $action['action'] == static::WEST)
                    ? $action['value'] * -1
                    : $action['value'];
            })
            ->sum();
    }

    protected function getManhattanDistanceForDirectionalActions(Collection $actions, $facingDirection)
    {
        $manhattanDistance = 0;

        while ($actions->isNotEmpty()) {
            $action = $actions->shift();

            if ($action['action'] == static::ROTATE_LEFT || $action['action'] == static::ROTATE_RIGHT) {
                $facingDirection = $this->turn($facingDirection, $action['action'] == static::ROTATE_RIGHT, $action['value']);
                continue;
            }

            // Forward in the facing direction
            $manhattanDistance += ($facingDirection == static::NORTH || $facingDirection == static::WEST)
                ? $action['value'] * -1
                : $action['value'];
        }

        return $manhattanDistance;
    }

    protected function turn($currentDirection, $clockwise, $degrees)
    {
        $mapping = $clockwise
            ? [static::NORTH => static::EAST, static::EAST => static::SOUTH, static::SOUTH => static::WEST, static::WEST => static::NORTH]
            : [static::NORTH => static::WEST, static::WEST => static::SOUTH, static::SOUTH => static::EAST, static::EAST => static::NORTH];

        while ($degrees > 0) {
            $currentDirection = $mapping[$currentDirection];
            $degrees -= 90;
        }

        return $currentDirection;
    }
}
