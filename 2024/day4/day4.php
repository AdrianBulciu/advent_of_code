<?php

$puzzle = fopen('input.txt', 'r');
$lineArray = [];

while( ($buffer = fgets($puzzle, 4096) ) !== false) {
    $lineArray[] = trim($buffer);
}

$lineLength = strlen($lineArray[0]);
$lineArrayLength = count($lineArray);

function getMatchesCount($line) {
    $matches = [];
    preg_match_all('/S(?=AMX)|X(?=MAS)/', $line, $matches, 0, 0);
    return count($matches[0]);
}

$result = 0;
$verticalLines = [];
$diagonalA = [];
$diagonalB = [];
$diagonalC = [];
$diagonalD = [];

foreach ($lineArray as $row => $line) {
    $result += getMatchesCount($line);

    for ($i = 0; $i < $lineLength; $i++) {
        @$verticalLines["$i"] .= $line[$i];

        $index = abs($i - $row);
        if ($i >= $row) {
            @$diagonalA["$index"] .= $line[$i];
        }
        else {
            @$diagonalB["$index"] .= $line[$i];
        }

        $index = $i + $row;
        if (($i + $row) < $lineLength) {
            @$diagonalC["$index"] .= $line[$i];
        }
        else {
            @$diagonalD["$index"] .= $line[$i];
        }
    }
}

for ($i = 0; $i < ($lineLength + $lineArrayLength); $i++) {
    $result += getMatchesCount($verticalLines[$i] ?? "");
    $result += getMatchesCount($diagonalA[$i] ?? "");
    $result += getMatchesCount($diagonalB[$i] ?? "");
    $result += getMatchesCount($diagonalC[$i] ?? "");
    $result += getMatchesCount($diagonalD[$i] ?? "");
}

echo $result . PHP_EOL;


// PART TWO //
$resultPartTwo = 0;

foreach ($lineArray as $key => $line) {
    for ($i = 1; $i < $lineLength - 1; $i++) {
        $letter = $line[$i];

        if ($letter !== "A") {
           continue; 
        }

        $valid = false;
        $aboveLeftLetter = $lineArray[$key - 1][$i - 1] ?? "";
        $aboveRightLetter = $lineArray[$key - 1][$i + 1] ?? "";
        $underLeftLetter = $lineArray[$key + 1][$i - 1] ?? "";
        $underRightLetter = $lineArray[$key + 1][$i + 1] ?? "";

        if ( 
            ($aboveLeftLetter === 'M' && $underRightLetter === 'S')
            &&
            ($aboveRightLetter === 'S' && $underLeftLetter === 'M')
        ) {
            $valid = true;
        }

        if ( 
            ($aboveLeftLetter === 'S' && $underRightLetter === 'M')
            &&
            ($aboveRightLetter === 'M' && $underLeftLetter === 'S')
        ) {
            $valid = true;
        }

        if ( 
            ($aboveLeftLetter === 'M' && $underRightLetter === 'S')
            &&
            ($aboveRightLetter === 'M' && $underLeftLetter === 'S')
        ) {
            $valid = true;
        }

        if ( 
            ($aboveLeftLetter === 'S' && $underRightLetter === 'M')
            &&
            ($aboveRightLetter === 'S' && $underLeftLetter === 'M')
        ) {
            $valid = true;
        }

        if ($valid) {
            $resultPartTwo++;
        }
    }
}

echo $resultPartTwo . PHP_EOL;
