<?php
class main {
    private $bags = [];
    private $charMap = [];
    const CHAR_a = 0141;
    const CHAR_A = 0101;

    function readInput($filename) {
        $lines = file($filename);

        foreach($lines as $line) {
            $line = trim($line);
            $bag = str_split(($line), strlen($line)/2);
            foreach ($bag as $key => $compartment) {
                $bag[$key] = str_split($compartment);
            }
            $this->bags[] = $bag;
        }
    }

    function getCommonCharacter($bag) {
        return array_intersect($bag[0],$bag[1]);
    }

    function getPriority($char) {
        if (!count($this->charMap)) {
            for ($i = 0; $i <= 25; $i++) {
                $this->charMap[$i + 1] = chr(self::CHAR_a + $i);
                $this->charMap[$i + 27] = chr(self::CHAR_A + $i);
            }
        }

        return array_search($char, $this->charMap);
    }

    function execute($filename) {
        $this->readInput($filename);
        $this->part1();
        echo PHP_EOL;
        $this->part2();
    }

    function part1() {
        $totalScore = 0;
        //foreach bag
        foreach ($this->bags as $bag) {
            $tempArr = $this->getCommonCharacter($bag);
            $commonChar = array_pop($tempArr);
            $priority = $this->getPriority($commonChar);
            $totalScore += $priority;
        }

        echo sprintf("The sum of the priorities is: %s", $totalScore);
    }
    function part2() {
        $totalScore =0;

        //chunk the array into groups of three
        $groupedBags = array_chunk($this->bags, 3);

        //find intersecting item
        foreach ($groupedBags as $group) {
            $commonChar = array_intersect(
                array_merge($group[0][0],$group[0][1]),
                array_merge($group[1][0],$group[1][1]),
                array_merge($group[2][0],$group[2][1])
            );
            $commonChar = array_pop($commonChar);

            //score the item
            //sum the scores
            $totalScore += $this->getPriority($commonChar);
        }

        echo sprintf("The sum of the priorities of the badges is: %s", $totalScore);
    }
}

$main = new main();
//$main->execute('test.txt');
$main->execute('input.txt');