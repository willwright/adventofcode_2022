<?php
class main {
    private $elfPairs = [];
    function readInput($filename) {
        $lines = file($filename);

        foreach($lines as $line) {
            $this->elfPairs[] = explode(',', trim($line));
        }
    }

    function execute($filename) {
        $this->readInput($filename);
        $this->part1();
        echo PHP_EOL;
        $this->part2();
    }

    function getLargestArrayCount($elf1, $elf2) {
        return count($elf1) > count($elf2) ? count($elf1) : count($elf2);
    }

    function part1() {
        $totalFullyOverlappedPairs = 0;

        foreach ($this->elfPairs as $elfPair) {
            $elf1 = $elfPair[0];
            $elf2 = $elfPair[1];

            $elf1Arr = range(explode('-',$elf1)[0],explode('-',$elf1)[1]);
            $elf2Arr = range(explode('-',$elf2)[0],explode('-',$elf2)[1]);

            $maxArrayCount = $this->getLargestArrayCount($elf1Arr, $elf2Arr);

            if ($maxArrayCount == count(array_unique(array_merge($elf1Arr, $elf2Arr)))) {
                $totalFullyOverlappedPairs++;
            }
        }

        echo sprintf("Number of pairs which are fully overlapped: %s", $totalFullyOverlappedPairs);
    }

    function part2() {
        $totalPartialOverlappedParis = 0;

        foreach ($this->elfPairs as $elfPair) {
            $elf1 = $elfPair[0];
            $elf2 = $elfPair[1];

            $elf1Arr = range(explode('-',$elf1)[0],explode('-',$elf1)[1]);
            $elf2Arr = range(explode('-',$elf2)[0],explode('-',$elf2)[1]);

            if (count(array_intersect($elf1Arr, $elf2Arr))) {
                $totalPartialOverlappedParis++;
            }
        }

        echo sprintf("Number of pairs which are partially overlapped: %s", $totalPartialOverlappedParis);
    }
}

$main = new main();
//$main->execute('test.txt');
$main->execute('input.txt');