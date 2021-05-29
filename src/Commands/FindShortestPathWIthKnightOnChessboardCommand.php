<?php

declare(strict_types=1);

namespace Commands;

use Field;
use ChessBoard;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindShortestPathWIthKnightOnChessboardCommand extends Command
{
    private const LETTER_TO_INDEX = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3, 'e' => 4, 'f' => 5, 'g' => 6, 'h' => 7];

    protected function configure()
    {
        $this->setName('find-shortest-path:knight');
        $this->addArgument('start', InputArgument::REQUIRED, 'Starting position of chess piece');
        $this->addArgument('goal', InputArgument::REQUIRED, 'Goal position of chess piece');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startCoords = $input->getArgument('start');
        $goalCoords = $input->getArgument('goal');

        $startField = $this->createFieldFromChessCoords($startCoords);
        $goalField = $this->createFieldFromChessCoords($goalCoords);

        $chessBoard = new ChessBoard();
        $result = $chessBoard->shortestHorsePath($startField, $goalField);

        if (count($result) === 0 ) {
            $output->writeln('There is no path, because the goal is on the same field as start');
        } else {
            $output->writeln('The shortest path is: ' . $this->pathToString($result));
        }

        return Command::SUCCESS;
    }

    private function createFieldFromChessCoords(string $chessCoords): Field
    {
        if (strlen($chessCoords) !== 2) {
            throw new InvalidArgumentException(sprintf('Chess coordination %s is invalid', $chessCoords));
        }

        $chessPositionX = substr($chessCoords, 0, 1);
        $chessPositionY = (int)substr($chessCoords, 1, 1);
        if (!isset(self::LETTER_TO_INDEX[$chessPositionX])) {
            throw new InvalidArgumentException(sprintf('Horizontal coordination %s is invalid', $chessPositionX));
        }

        if ($chessPositionY < 1 || $chessPositionY > 8) {
            throw new InvalidArgumentException(sprintf('Vertical coordination %s is invalid', $chessPositionY));
        }

        return new Field(self::LETTER_TO_INDEX[$chessPositionX], $chessPositionY - 1);
    }

    /**
     * @param Field[] $fields
     */
    private function pathToString(array $fields): string
    {
        $pathAsString = $this->fieldToChessCoords($fields[0]);

        for ($fieldIndex = 1; $fieldIndex < count($fields); $fieldIndex++) {
            $pathAsString = sprintf('%s -> %s', $pathAsString, $this->fieldToChessCoords($fields[$fieldIndex]));
        }

        return $pathAsString;
    }

    private function fieldToChessCoords(Field $field): string
    {
        $indexToLetter = array_flip(self::LETTER_TO_INDEX);

        return sprintf('%s%d', $indexToLetter[$field->getX()], $field->getY() + 1);
    }
}
