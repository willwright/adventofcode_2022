<?php

class Game {
    public $player1;
    public $player2;
}
class main {
    /**
     * A,X = Rock
     * B,Y = Paper
     * C,Z = Scissors
     */
    const LOSS = 0;
    const DRAW = 3;
    const WIN = 6;

    /**
     * @var array
     */
    public $games = [];

    function readInput($filename) {
        $lines = file($filename);

        foreach($lines as $line) {
            /** @var Game $game */
            $game = new Game();
            $game->player1 = substr($line,0,1);
            $game->player2 = substr($line,2,1);
            $this->games[] = $game;
        }
    }

    function getOutcomePoints(Game $game) {
        switch ($game->player1) {
            case 'A':
                switch ($game->player2) {
                    case 'X':
                        return self::DRAW;
                    case 'Y':
                        return self::WIN;
                    case 'Z':
                        return self::LOSS;
                }
                break;
            case 'B':
                switch ($game->player2) {
                    case 'X':
                        return self::LOSS;
                    case 'Y':
                        return self::DRAW;
                    case 'Z':
                        return self::WIN;
                }
                break;
            case 'C':
                switch ($game->player2) {
                    case 'X':
                        return self::WIN;
                    case 'Y':
                        return self::LOSS;
                    case 'Z':
                        return self::DRAW;
                }
                break;
        }
    }

    function getPlayerPoints(Game $game) {
        switch ($game->player2){
            case 'X':
                return 1;
                break;
            case 'Y':
                return 2;
                break;
            case 'Z':
                return 3;
                break;
        }
    }

    function alterPlayers(Game &$game) {
        switch ($game->player2) {
            case 'X':
                //Loss
                switch ($game->player1) {
                    case 'A':
                        $game->player2 = 'Z';
                        return;
                    case 'B':
                        $game->player2 = 'X';
                        return;
                    case 'C':
                        $game->player2 = 'Y';
                        return;
                }
                break;
            case 'Y':
                //Draw
                switch ($game->player1) {
                    case 'A':
                        $game->player2 = 'X';
                        return;
                    case 'B':
                        $game->player2 = 'Y';
                        return;
                    case 'C':
                        $game->player2 = 'Z';
                        return;
                }
                break;
            case 'Z':
                //Win
                switch ($game->player1) {
                    case 'A':
                        $game->player2 = 'Y';
                        return;
                    case 'B':
                        $game->player2 = 'Z';
                        return;
                    case 'C':
                        $game->player2 = 'X';
                        return;
                }
                break;
        }
    }

    function scoreGame(Game $game) {
        $score = 0;
        $score += $this->getOutcomePoints($game);
        $score += $this->getPlayerPoints($game);
        return $score;
    }

    function execute($filename) {
        $this->readInput($filename);
        $this->part1();
        $this->part2();
    }

    function part1() {
        $totalScore = 0;
        foreach ($this->games as $game) {
            $totalScore += $this->scoreGame($game);
        }

        echo sprintf("Score for this strategy: %s", $totalScore);
        echo PHP_EOL;
    }

    function part2() {
        $totalScore = 0;
        foreach ($this->games as $game) {
            $this->alterPlayers($game);
            $totalScore += $this->scoreGame($game);
        }

        echo sprintf("Score for this strategy: %s", $totalScore);
        echo PHP_EOL;
    }
}

$main = new main();
//$main->execute('test.txt');
$main->execute('input.txt');