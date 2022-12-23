<?php

class Valve {
    public $id;
    public $flowRate;
    public array $tunnels;
    public bool $isOpen = false;
}

class Main
{
    public $totalPressureReleased = 0;
    public $ellapsedTime = 0;

    public $valves = [];
    public Valve $currentValve;
    function readInput($filename) {
        $lines = file($filename);

        foreach($lines as $line) {
            preg_match('/Valve\s(?<id>[A-G].)/',$line,$matches);
            $valve = new Valve();
            $valve->id = trim($matches['id']);

            preg_match('/\w*flow rate=(?<rate>\d*);/', $line, $matches);
            $valve->flowRate = trim($matches['rate']);

            preg_match('/.*valves (.*)/', $line, $matches);
            $valve->tunnels = explode(',', $matches[1]);
            array_walk($valve->tunnels, function(&$item){
                $item = trim($item);
            });

            $this->valves[] = $valve;
        }
    }

    function moveToValve(Valve $valve) {
        if ($this->ellapsedTime >= 30) {
            throw new Exception("Out of Time");
        }
        $this->currentValve = $valve;
        $this->ellapsedTime++;

        echo sprintf("Minute: %s", $this->ellapsedTime);
        echo PHP_EOL;
        echo sprintf("Move to valve: %s", $valve->id);
        echo PHP_EOL;
    }

    function openValve(Valve $valve) {
        if ($this->ellapsedTime >= 30) {
            throw new Exception("Out of Time");
        }

        $valve->isOpen = true;
        $this->ellapsedTime++;

        echo sprintf("Minute: %s", $this->ellapsedTime);
        echo PHP_EOL;
        echo sprintf("Open valve: %s", $valve->id);
        echo PHP_EOL;
    }

    function collectTotals() {
        foreach ($this->valves as $valve) {
            /** @var Valve $valve */
            if ($valve->isOpen) {
                $this->totalPressureReleased += $valve->flowRate;
            }
        }
    }

    function getValveById($id) {
        foreach ($this->valves as $valve) {
            /** @var Valve $valve */
            if ($valve->id == $id) {
                return $valve;
            }
        }

        return false;
    }

    function getBestNextValveFromValve(Valve $valve) {
        //Sort valves by flowrate
        //go down the list and move to the first one that is closed

        usort($valve->tunnels, function($a,$b){
            $valveA = $this->getValveById($a);
            $valveB = $this->getValveById($b);
            if ($valveB->flowRate == $valveA->flowRate) return 0;
            if ($valveB->flowRate > $valveA->flowRate) {
                return 1;
            } else {
                return -1;
            }
        });

        foreach ($valve->tunnels as $tunnel) {
            $nextValve = $this->getValveById($tunnel);
            if (!$nextValve->isOpen) {
                return $nextValve;
            }
        }
    }

    public function run($fileName) {
        //Create a function to create all the Valve objects
        $this->readInput($fileName);

        $this->currentValve = $this->valves[0];

        if (
            !$this->currentValve->isOpen &&
            $this->currentValve->flowRate > 0
        ) {
            $this->openValve($this->currentValve);
        }

        while ($this->ellapsedTime < 30) {
            $highestFlowRateValve = $this->getBestNextValveFromValve($this->currentValve);
            try {
                $this->moveToValve($highestFlowRateValve);
            } catch (Exception $e) {
            }

            $this->collectTotals();

            if (
                !$this->currentValve->isOpen &&
                $this->currentValve->flowRate > 0
            ) {
                $this->openValve($this->currentValve);
            }
        }

        echo sprintf("Total pressure released: %s", $this->totalPressureReleased);
    }
}

$main = new Main();
$main->run('test.txt');