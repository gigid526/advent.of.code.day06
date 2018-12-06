<?php
$xs = $ys = [];
$coordinates = array_map(function ($line) use (&$xs, &$ys) {
    $coordinate = explode(', ', $line);
    $xs[] = (int) $coordinate[0];
    $ys[] = (int) $coordinate[1];
    return [(int) $coordinate[0], (int) $coordinate[1]];
}, file(__DIR__ . '/input.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));
// the first puzzle
$bounds = [min($xs), max($xs), min($ys), max($ys)];
$fifo = array_map(function ($coordinate, $id) {
    return [$coordinate[0], $coordinate[1], 0, $id];
}, $coordinates, array_keys($coordinates));
$visitedCoordinates = [];
$infiniteCoordinates = [];
while (count($fifo) > 0) {
    list($x, $y, $distance, $id) = array_shift($fifo);
    if ($x < $bounds[0] || $x > $bounds[1] || $y < $bounds[2] || $y > $bounds[3]) {
        $infiniteCoordinates[$id] = $id;
        continue;
    }
    $key = $x . '-' . $y;
    if (isset($visitedCoordinates[$key])) {
        if ($visitedCoordinates[$key][2] === $distance && $visitedCoordinates[$key][3] !== $id) {
            $visitedCoordinates[$key][3] = null;
        }
        continue;
    }
    $visitedCoordinates[$key] = [$x, $y, $distance, $id];
    foreach ([[-1,0],[1,0],[0,-1],[0,1]] as $translation) {
        $tx = $x + $translation[0];
        $ty = $y + $translation[1];        
        array_push($fifo, [$tx, $ty, $distance + 1, $id]);
    }
}
$areas = [];
foreach ($visitedCoordinates as $coordinate) {
    if (in_array($coordinate[3], $infiniteCoordinates) === false) {
        isset($areas[$coordinate[3]]) ? $areas[$coordinate[3]]++ : ($areas[$coordinate[3]] = 1);
    }
}
echo max($areas) . PHP_EOL;
// the second puzzle
$region = [];
for ($y = $bounds[2]; $y <= $bounds[3]; ++$y) {
    for ($x = $bounds[0]; $x <= $bounds[1]; ++$x) {
        $distance = 0;
        foreach ($coordinates as $coordinate) {
            $distance += abs($x - $coordinate[0]) + abs($y - $coordinate[1]);
            if ($distance >= 10000) {
                break;
            }
        }
        if ($distance < 10000) {
            array_push($region, [$x, $y]);
        }
    }
}
echo count($region) . PHP_EOL;



