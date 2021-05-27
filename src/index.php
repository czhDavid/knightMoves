<?php
declare(strict_types=1);
require 'autoload.php';

$chessBoard = new ChessBoard();

$shortestPath = $chessBoard->find(new Field(0, 0), new Field(8, 8));

var_dump($shortestPath);

