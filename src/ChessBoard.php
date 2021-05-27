<?php

declare(strict_types=1);

class ChessBoard{


    public function __construct(private int $horizontalSize = 8, private int $verticalSize = 8) {}

    private const AVAILABLE_KNIGHT_MOVES = [
        [1, 2],
        [2, 1],
        [2, -1],
        [1, -2],
        [-1, -2],
        [-2, -1],
        [-2, 1],
        [-1, 2],
    ];

    public function find(Field $start, Field $goal): array
    {
        if (!$this->isFieldOnBoard($start) || !$this->isFieldOnBoard($goal)) {
            return [];
        }

        $visitedCords = [$start];
        $paths[] = [$start];

        while(true) {
            $foundNewPath = false;
            foreach ($paths as $key => $path) {
                $foundNewPathExtension = false;
                foreach (self::AVAILABLE_KNIGHT_MOVES as $knightMove) {
                    $newMove = new Field(end($path)->x + $knightMove[0], end($path)->y + $knightMove[1]);
                    //are we still on board
                    if (!$this->isFieldOnBoard($newMove)) {
                        continue;
                    }

                    // did we already visit this cord before? no need to check again
                    if (in_array($newMove, $visitedCords)) {
                        continue;
                    }

                    // found the goal, great job
                    if ($newMove == $goal) {
                        return [...$path, $newMove];
                    }

                    //add new path to list of paths
                    $paths[] = [...$path, $newMove];

                    $visitedCords[] = $newMove;

                    $foundNewPath = true;
                    $foundNewPathExtension = true;
                }

                //remove dead paths to speed up the search and don't revisit dead paths
                if (!$foundNewPathExtension) {
                    unset($paths[$key]);
                }
            }

            //if we did not find a solution and no new path exit;
            if (!$foundNewPath) {
                return [];
            }
        }
    }

    private function isFieldOnBoard(Field $field): bool
    {
        return $field->x <= $this->horizontalSize && $field->y <= $this->verticalSize;
    }
}

