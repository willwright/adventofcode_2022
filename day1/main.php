<?php

class Elf {
    public $totalCalories;
}
class main {
    public $elves;

    function readInput($filename) {
        $lines = file($filename);

        $elf = new Elf();

        foreach($lines as $line) {
            if ($line != PHP_EOL) {
                $elf->totalCalories += trim($line);
            } else {
                $this->elves[] = $elf;
                $elf = new Elf();
            }
        }
    }
    function execute($filename) {
        $this->readInput($filename);

        /** @var Elf $biggestElf */
        $biggestElf = $this->elves[0];
        $biggestElfIdx = 0;
        foreach ($this->elves as $key => $elf) {
            /** @var Elf $elf */
            if ($elf->totalCalories > $biggestElf->totalCalories) {
                $biggestElf = $elf;
                $biggestElfIdx = $key;
            }
        }

        echo sprintf("Elf with the most calories is: %s, with %s calories", $biggestElfIdx+1, $this->elves[$biggestElfIdx]->totalCalories);
    }
}

$main = new main();
$main->execute('input.txt');