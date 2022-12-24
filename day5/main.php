<?php
class main {

//    private $stacks = [
//        1 => ['Z','N'],
//        2 => ['M','C','D'],
//        3 => ['P']
//    ];
//
    private $stacks = [
        1 => ['D','T','R','B','J','L','W','G'],
        2 => ['S','W','C'],
        3 => ['R','Z','T','M'],
        4 => ['D','T','C','H','S','P','V'],
        5 => ['G','P','T','L','D','Z'],
        6 => ['F','B','R','Z','J','Q','C','D'],
        7 => ['S','B','D','J','M','F','T','R'],
        8 => ['L','H','R','B','T','V','M'],
        9 => ['Q','P','D','S','V']
    ];

    private $moves = [];

    function readInput($filename) {
        $lines = file($filename);

        foreach($lines as $line) {
            if (stripos($line, 'move') === 0) {
                $this->moves[] = trim($line);
            }
        }
    }

    function execute($filename) {
        $this->readInput($filename);
        $this->part1();
        echo PHP_EOL;
        $this->part2();
    }

    function part1() {
        $stacks = $this->stacks;
        foreach ($this->moves as $move) {
            preg_match('/move (?<numCrate>\d*)/', $move, $numCratesToMove);
            preg_match('/from (?<fromstack>\d*)/', $move, $fromStack);
            preg_match('/to (?<tostack>\d*)/', $move, $toStack);

            $stacks[$toStack['tostack']] = array_merge($stacks[$toStack['tostack']], array_reverse(array_slice($stacks[$fromStack['fromstack']], -$numCratesToMove['numCrate'])));
            for ($i = 0; $i < $numCratesToMove['numCrate']; $i++) {
                array_pop($stacks[$fromStack['fromstack']]);
            }
        }

        $finalString = '';
        foreach ($stacks as $stack) {
            $finalString .= array_pop($stack);
        }

        echo sprintf("What create is on the top of each stack: %s", $finalString);
    }

    function part2() {
        $stacks = $this->stacks;
        foreach ($this->moves as $move) {
            preg_match('/move (?<numCrate>\d*)/', $move, $numCratesToMove);
            preg_match('/from (?<fromstack>\d*)/', $move, $fromStack);
            preg_match('/to (?<tostack>\d*)/', $move, $toStack);

            $stacks[$toStack['tostack']] = array_merge($stacks[$toStack['tostack']], array_slice($stacks[$fromStack['fromstack']], -$numCratesToMove['numCrate']));
            for ($i = 0; $i < $numCratesToMove['numCrate']; $i++) {
                array_pop($stacks[$fromStack['fromstack']]);
            }
        }

        $finalString = '';
        foreach ($stacks as $stack) {
            $finalString .= array_pop($stack);
        }

        echo sprintf("What create is on the top of each stack: %s", $finalString);
    }
}

$main = new main();
//$main->execute('test.txt');
$main->execute('input.txt');