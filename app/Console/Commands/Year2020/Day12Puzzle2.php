<?php

namespace App\Console\Commands\Year2020;

class Day12Puzzle2 extends Day12Puzzle1
{
    protected int $part = 2;

    /**
     * Solve the puzzle.
     *
     * @param string $data
     *
     * @return void
     */
    protected function solve(string $data)
    {
        $actions = $this->explodePerLine($data)
            ->map(function ($line) {
                return [
                    'action' => substr($line, 0, 1),
                    'value' => (int) substr($line, 1),
                ];
            });

        $shipLocation = [0, 0];
        $waypointLocation = [10, -1]; // 10 east, 1 north

        while ($actions->isNotEmpty()) {
            $action = $actions->shift();

            if ($action['action'] == static::FORWARD) {
                $shipLocation = $this->moveShip($shipLocation, $waypointLocation, $action['value']);
                continue;
            }

            if ($action['action'] == static::ROTATE_LEFT || $action['action'] == static::ROTATE_RIGHT) {
                $waypointLocation = $this->rotateWaypoint($waypointLocation, $action['action'] == static::ROTATE_RIGHT, $action['value']);
                continue;
            }

            // Move waypoint a given direction
            if ($action['action'] == static::NORTH) {
                $waypointLocation[1] -= $action['value'];
            } elseif ($action['action'] == static::SOUTH) {
                $waypointLocation[1] += $action['value'];
            } elseif ($action['action'] == static::EAST) {
                $waypointLocation[0] += $action['value'];
            } elseif ($action['action'] == static::WEST) {
                $waypointLocation[0] -= $action['value'];
            }
        }

        $this->answer = abs($shipLocation[0]) + abs($shipLocation[1]);
    }

    protected function moveShip($currentLocation, $waypointLocation, $times)
    {
        $currentLocation[0] += $waypointLocation[0] * $times;
        $currentLocation[1] += $waypointLocation[1] * $times;

        return $currentLocation;
    }

    protected function rotateWaypoint($currentLocation, $clockwise, $degrees)
    {
        $tmpLocation = $currentLocation;

        // Perform the rotations
        while ($degrees > 0) {
            $tmpLocation = $clockwise
                ? [$tmpLocation[1] * -1, $tmpLocation[0]]
                : [$tmpLocation[1], $tmpLocation[0] * -1];

            $degrees -= 90;
        }

        return $tmpLocation;

    }
}
