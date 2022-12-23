<?php
ini_set('memory_limit', '-1');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);



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
    const FREQUENCY = 4000000;
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
                !$this->isBeacon($key, $row)
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

//                if (($sensor->y + $yDisplacement) != $searchRow) {
//                    continue;
//                }

                if (($sensor->y + $yDisplacement) < 0 || ($sensor->y + $yDisplacement) > 4000000) {
                    continue;
                }

                for ($j = -$xDisplacement; $j <= $xDisplacement; $j++) {
                    if (($sensor->x + $j) < 0 || ($sensor->x + $j) > 4000000) {
                        continue;
                    }

                    if (isset($this->map[$sensor->y + $yDisplacement][$sensor->x + $j])) {
                        continue;
                    } else {
                        $this->map[$sensor->y + $yDisplacement][$sensor->x + $j] = false;
                    }
                }
            }
        }

        $answer = $this->getNotPositionsForRow($searchRow);
        echo sprintf("Answer is %s", $answer) . PHP_EOL;

        // PART2
        //Loop over the graph inside the given search parameters
        //If the map position is not set AND it is not a sensor or a beacon then report it out
        //multiple by the frequncy and add the y coordinate
        ksort($this->map);
        $beaconX = 0;
        $beaconY = 0;
        for ($y = 0; $y <= 4000000; $y++) {
            ksort($this->map[$y]);
            for ($x = 0; $x <= 4000000; $x++) {
                if (
                    !isset($this->map[$y][$x])
                    && !$this->isSensor($x,$y)
                    && !$this->isBeacon($x,$y)
                ) {
                    echo sprintf("The distress beacon is at (X,Y): %s,%s", $x,$y) . PHP_EOL;
                    $beaconX = $x;
                    $beaconY = $y;
                    break;
                }
            }
        }

        echo sprintf("The frequency is: %s", ($beaconX * self::FREQUENCY) + $beaconY) . PHP_EOL;
    }
}

$main = new Main();
//$main->run('test.txt', 10);
$main->run('input.txt', 2000000);