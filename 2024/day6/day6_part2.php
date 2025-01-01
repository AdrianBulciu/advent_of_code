<?php

$map = file('input.txt', FILE_IGNORE_NEW_LINES);
$mapLength = count($map);
$corridorLength = strlen($map[0]);
$guardPosition = [];

foreach($map as $key => $path) {
    if (str_contains($path, '^')) {
        $guardPosition = [$key, stripos($path, '^')];
        break;
    }
}

$originalGuardPos = $guardPosition;
$blocksReached = [];
$direction = 'up';
$partTwo = false;
$found = false;
$loopsFound = 0;
$positions = 0;

function checkNextPositionIsWithinBounds($currentPosition, $direction) {
    $currentCoridor = $currentPosition[0];
    $coridorIndex = $currentPosition[1];

    if ($direction === 'up') {
        return ($currentCoridor - 1) >= 0;
    }
    else if ($direction === 'down') {
        return ($currentCoridor + 1) < $GLOBALS['mapLength'];
    }
    else if ($direction === 'right') {
        return ($coridorIndex + 1) < $GLOBALS['corridorLength'];
    }
    else {
        return ($coridorIndex - 1) >= 0;
    }
}

function walk(&$currentPosition, &$direction, &$map) {
    $currentCoridor = $currentPosition[0];
    $coridorIndex = $currentPosition[1];

    if ($GLOBALS["found"]) {
        return;
    }

    if ($direction === 'up') {
        $nextPosition = [$currentCoridor - 1, $coridorIndex];
        if (canMoveToNextPosition($nextPosition, $currentPosition, $map, $direction)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'right';
        return walk($currentPosition, $direction, $map);
    }
    else if ($direction === 'down') {
        $nextPosition = [$currentCoridor + 1, $coridorIndex];
        if (canMoveToNextPosition($nextPosition, $currentPosition, $map, $direction)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'left';
        return walk($currentPosition, $direction, $map);
    }
    else if ($direction === 'right') {
        $nextPosition = [$currentCoridor, $coridorIndex + 1];
        if (canMoveToNextPosition($nextPosition, $currentPosition, $map, $direction)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'down';
        return walk($currentPosition, $direction, $map);
    }
    else {
        $nextPosition = [$currentCoridor, $coridorIndex - 1];
        if (canMoveToNextPosition($nextPosition, $currentPosition, $map, $direction)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'up';
        return walk($currentPosition, $direction, $map);
    }
}

function move(&$currentPosition, $nextPosition, &$map) {
    if ($GLOBALS["partTwo"]) {
        $currentPosition = $nextPosition;
        return;
    }

    if ($map[$nextPosition[0]][$nextPosition[1]] !== 'X') {
        $GLOBALS['positions']++;
    }

    $map[$nextPosition[0]][$nextPosition[1]] = 'X';
    $currentPosition = $nextPosition;
}

function canMoveToNextPosition($nextPosition, $currentPosition, $map, $direction) {
    $can = !in_array($map[$nextPosition[0]][$nextPosition[1]], ['#', '0']);

    if (!$can && $GLOBALS["partTwo"]) {
        $blockId = implode('-', $currentPosition) . $direction;

        if (isset($GLOBALS["blocksReached"][$blockId])) { 
            $GLOBALS['loopsFound']++;
            $GLOBALS['found'] = true;
        }
        else {
            $GLOBALS["blocksReached"][$blockId] = 1;
        }
    }

    return $can;
}


if ($map[$guardPosition[0]][$guardPosition[1]] !== 'X') {
    $map[$guardPosition[0]][$guardPosition[1]] = 'X';
    $positions++;
}

while (true) {
    walk($guardPosition, $direction, $map);
    if (!checkNextPositionIsWithinBounds($guardPosition, $direction)) {
        break;
    }
}

echo "Part one: " . $positions . PHP_EOL;

$partTwo = true;

for ($i = 0; $i < $mapLength; $i++) {
    for($j = 0; $j < $corridorLength; $j++) {
        $copyOfMap = $map;
        if ($copyOfMap[$i][$j] == 'X' && [$i, $j] != $originalGuardPos) {
            $found = false;
            $blocksReached = [];
            $copyOfGuardPosition = $originalGuardPos;
            $copyOfDirection = "up";
            $copyOfMap[$i][$j] = '0';

            while (true) {
                walk($copyOfGuardPosition, $copyOfDirection, $copyOfMap);
                if (!checkNextPositionIsWithinBounds($copyOfGuardPosition, $copyOfDirection) || $GLOBALS["found"]) {
                    break;
                }
            }
        }
    }
}

echo "Part two: " . $loopsFound . PHP_EOL;
