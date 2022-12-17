<?php

class Sensor {
    public $x;
    public $y;
    public Beacon $closestBeacon;
}
class Beacon {
    public $x;
    public $y;
}

class Main {
    public array $input = [];

    public array $map = [];

    function getLowerX($sensors) {
        $leastX = 0;
        foreach ($sensors as $sensor) {
            /** @var $sensor Sensor */
            if ($sensor->x < $leastX) {
                $leastX = $sensor->x;
            }
            if ($sensor->closestBeacon->x < $leastX) {
                $leastX = $sensor->closestBeacon->x;
            }
        }

        return $leastX;
    }

    function getUpperX($sensors) {
        $greatestX = 0;
        foreach ($sensors as $sensor) {
            /** @var $sensor Sensor */
            if ($sensor->x > $greatestX) {
                $greatestX = $sensor->x;
            }
            if ($sensor->closestBeacon->x > $greatestX) {
                $greatestX = $sensor->closestBeacon->x;
            }
        }

        return $greatestX;
    }

    function getLowerY($sensors) {
        $leastY = 0;
        foreach ($sensors as $sensor) {
            /** @var $sensor Sensor */
            if ($sensor->y < $leastY) {
                $leastY = $sensor->y;
            }
            if ($sensor->closestBeacon->y < $leastY) {
                $leastY = $sensor->closestBeacon->y;
            }
        }

        return $leastY;
    }

    function getUpperY($sensors) {
        $greatestY = 0;
        foreach ($sensors as $sensor) {
            /** @var $sensor Sensor */
            if ($sensor->y > $greatestY) {
                $greatestY = $sensor->y;
            }
            if ($sensor->closestBeacon->y > $greatestY) {
                $greatestY = $sensor->closestBeacon->y;
            }
        }

        return $greatestY;
    }

    function getDistanceToBeacon(Sensor $sensor) {
        $deltaX = abs($sensor->x - $sensor->closestBeacon->x);
        $deltaY = abs($sensor->y - $sensor->closestBeacon->y);

        return (int) $deltaY + $deltaX;
    }

    function isSensor($x, $y) {
        foreach ($this->input as $sensor) {
            /** @var Sensor $sensor */
            if ($sensor->x == $x && $sensor->y == $y) {
                return true;
            }
        }

        return false;
    }

    function isBeacon($x, $y) {
        foreach ($this->input as $sensor) {
            /** @var Sensor $sensor */
            if ($sensor->closestBeacon->x == $x && $sensor->closestBeacon->y == $y) {
                return true;
            }
        }

        return false;
    }

    function getNotPositionsForRow($row) {
        ksort($this->map[$row]);
        $count = 0;
        foreach ($this->map[$row] as $key => $value) {
            if (
                $key >= $this->getLowerX($this->input) &&
                $key <= $this->getUpperX($this->input) &&
                !$this->isSensor($key, $row)
            ) {
                $count++;
            }
        }

        return $count;
    }

    public function readInput($filename) {
        $lines = file($filename);

        foreach($lines as $line) {
            $lineParts = explode(':', $line);
            $sensorPart = $lineParts[0];
            $beaconPart = $lineParts[1];

            $sensorPart = str_ireplace("Sensor at ", '', $sensorPart);
            $sensorPartX = explode(',', $sensorPart)[0];
            $sensorPartY = explode(',', $sensorPart)[1];

            $sensor = new Sensor();
            parse_str($sensorPartX, $sensor->x);
            parse_str($sensorPartY, $sensor->y);
            $sensor->x = $sensor->x['x'];
            $sensor->y = $sensor->y['y'];

            $beaconPart = str_ireplace(" closest beacon is at ", '', $beaconPart);
            $beaconPartX = explode(',', $beaconPart)[0];
            $beaconPartY = explode(',', $beaconPart)[1];

            $beacon = new Beacon();
            parse_str(trim($beaconPartX), $beacon->x);
            parse_str(trim($beaconPartY), $beacon->y);

            $beacon->x = $beacon->x['x'];
            $beacon->y = $beacon->y['y'];
            $sensor->closestBeacon = $beacon;
            $this->input[] = $sensor;
        }
    }

    public function run($filename, $searchRow) {
        $this->readInput($filename);

        foreach ($this->input as $sensor) {
            $distance = $this->getDistanceToBeacon($sensor);
            for ($i = $distance;  $i >= (0-$distance); $i--) {
                $yDisplacement = $i;
                $xDisplacement = $distance - abs($i);

                if (($sensor->y + $yDisplacement) != $searchRow) {
                    continue;
                }

                for ($j = -$xDisplacement; $j <= $xDisplacement; $j++) {
                    if (isset($this->map[$sensor->y + $yDisplacement][$sensor->x + $j])) {
                        continue;
                    } else {
                        $this->map[$sensor->y + $yDisplacement][$sensor->x + $j] = false;
                    }
                }
            }
        }

        $answer = $this->getNotPositionsForRow($searchRow);
        echo sprintf("Answer is %s", $answer);
    }
}

$main = new Main();
$main->run('input.txt', 2000000);