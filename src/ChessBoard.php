<?php

declare(strict_types=1);

class ChessBoard
{
    private const HORIZONTAL_SIZE = 8;
    private const VERTICAL_SIZE = 8;

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

    public function shortestHorsePath(Field $start, Field $goal): array
    {
        if (!$this->isFieldOnBoard($start) || !$this->isFieldOnBoard($goal)) {
            throw new LogicException('Start or goal field is not positioned on board.');
        }

        if($this->areFieldEqueal($start, $goal)) {
            return [];
        }

        $visitedPositions = [$start];
        $paths[] = [$start];

        while (count($paths) !== 0) {
            foreach ($paths as $key => $path) {
                foreach (self::AVAILABLE_KNIGHT_MOVES as $knightMove) {
                    $newPosition = new Field(end($path)->getX() + $knightMove[0], end($path)->getY() + $knightMove[1]);

                    if ($this->areFieldEqueal($newPosition, $goal)) {
                        return [...$path, $newPosition];
                    }

                    if (!$this->isFieldOnBoard($newPosition)) {
                        continue;
                    }

                    if ($this->wasFieldAlreadyVisited($newPosition, $visitedPositions)) {
                        continue;
                    }

                    $paths[] = [...$path, $newPosition];

                    $visitedPositions[] = $newPosition;
                }

                unset($paths[$key]);
            }
        }

        throw new LogicException('Path connecting start and goal does not exist');
    }

    private function isFieldOnBoard(Field $field): bool
    {
        return $field->getX() < self::HORIZONTAL_SIZE && $field->getY() < self::VERTICAL_SIZE && $field->getX() >= 0 && $field->getY() >= 0;
    }

    private function areFieldEqueal(Field $a, Field $b): bool
    {
        return $a->getX() === $b->getX() && $a->getY() === $b->getY();
    }

    private function wasFieldAlreadyVisited(Field $field, array $visitedFields): bool
    {
        foreach ($visitedFields as $visitedField) {
            if ($this->areFieldEqueal($field, $visitedField)) {
                return true;
            }
        }

        return false;
    }
}
