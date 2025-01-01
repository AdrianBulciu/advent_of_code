<?php
$map = file('sample.txt', FILE_IGNORE_NEW_LINES);
$mapLength = count($map);
$corridorLength = strlen($map[0]);
$guardPosition = [];

foreach($map as $key => $path) {
    if (str_contains($path, '^')) {
        $guardPosition = [$key, stripos($path, '^')];
        break;
    }
}

$direction = 'up';
$withinBounds = checkNextPositionIsWithinBounds($guardPosition, $direction); 
$positions = 1;

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

    if ($direction === 'up') {
        $nextPosition = [$currentCoridor - 1, $coridorIndex];
        if (canMoveToNextPosition($nextPosition, $map)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'right';
        return walk($currentPosition, $direction, $map);
    }
    else if ($direction === 'down') {
        $nextPosition = [$currentCoridor + 1, $coridorIndex];
        if (canMoveToNextPosition($nextPosition, $map)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'left';
        return walk($currentPosition, $direction, $map);
    }
    else if ($direction === 'right') {
        $nextPosition = [$currentCoridor, $coridorIndex + 1];
        if (canMoveToNextPosition($nextPosition, $map)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'down';
        return walk($currentPosition, $direction, $map);
    }
    else {
        $nextPosition = [$currentCoridor, $coridorIndex - 1];
        if (canMoveToNextPosition($nextPosition, $map)) {
            move($currentPosition, $nextPosition, $map);
            return;
        }
        $direction = 'up';
        return walk($currentPosition, $direction, $map);
    }
}

function move(&$currentPosition, $nextPosition, &$map) {
    if ($map[$nextPosition[0]][$nextPosition[1]] !== 'X') {
        $GLOBALS['positions']++; 
    } 

    $map[$nextPosition[0]][$nextPosition[1]] = '^';
    $map[$currentPosition[0]][$currentPosition[1]] = 'X';
    $currentPosition = $nextPosition;
}

function canMoveToNextPosition($nextPosition, $map) {
    return in_array($map[$nextPosition[0]][$nextPosition[1]], ['.', 'X']);
}

while ($withinBounds) {
    walk($guardPosition, $direction, $map);
    $withinBounds = checkNextPositionIsWithinBounds($guardPosition, $direction); 
}

print_r($map);
