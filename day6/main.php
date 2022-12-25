<?php
class main {
    private $uniqueChars = [];
    private $stream;

    function readInput($filename) {
        $lines = file($filename);

        foreach($lines as $line) {
            $this->stream = trim($line);
        }
    }

    function execute($filename) {
        $this->readInput($filename);
        $this->part1();
        echo PHP_EOL;
        $this->part2();
    }

    function part1() {
        $offset = 0;
        do {
            $signalMarkerTest = array_slice(str_split($this->stream), $offset, 4);
            if (count(array_unique($signalMarkerTest)) == 4){
                break;
            }
            $offset++;
        } while (
            (4+$offset) <= strlen($this->stream)
        );

        echo sprintf("Number of characters to first signal marker: %s", ($offset + 4));
    }

    function part2() {
        $offset = 0;
        do {
            $signalMarkerTest = array_slice(str_split($this->stream), $offset, 14);
            if (count(array_unique($signalMarkerTest)) == 14){
                break;
            }
            $offset++;
        } while (
            (14 + $offset) <= strlen($this->stream)
        );

        echo sprintf("Number of characters to first 14char signal marker: %s", ($offset + 14));
    }
}

$main = new main();
//$main->execute('test.txt');
$main->execute('input.txt');