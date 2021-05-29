<?php

declare(strict_types=1);

class Field {
    public function __construct(
        private int $x,
        private int $y
    ) {}

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
