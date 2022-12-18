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
        $this->elves[] = $elf;
    }
    function execute($filename) {
        $this->readInput($filename);

        rsort($this->elves);
        $totalCalories = 0;
        for ($i = 0; $i < 3; $i++) {
            $totalCalories += $this->elves[$i]->totalCalories;
        }

        echo sprintf("Calories carried by top 3 elves is: %s", $totalCalories);
    }
}

$main = new main();
$main->execute('input.txt');