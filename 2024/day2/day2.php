<?php

/*
	SETUP
*/

function isReportSafe($report, $badLevelTolerance = false) {
	$isSafe = true;
	$direction = ($report[1] > $report[0]);
	$count = count($report);

	if ($count <= 1) {
		return $isSafe;
	}

	for ($i = 1; $i < $count; $i++) {
		$adjacentDifference = abs($report[$i - 1] - $report[$i]);

		if (($adjacentDifference > 3) || ($adjacentDifference < 1)) {
			$isSafe = false;
			break;
		}

		$currentDirection = ($report[$i] > $report[$i - 1]);

		if ($currentDirection !== $direction) {
			$isSafe = false;
			break;
		}
	}

	if (!$isSafe && $badLevelTolerance) {
		for ($i = 0; $i < $count; $i++) {
			$reportCopy = $report;
			array_splice($reportCopy, $i, 1);

			if (isReportSafe($reportCopy, false)) {
				return true;
			}
		}
	}

	return $isSafe;
}

/*
	PART ONE
*/

$file = fopen('input.csv', 'r');
$result = 0;

while (($line = fgetcsv($file)) !== FALSE) {
	$isSafe = true;
	$report = explode(' ', $line[0]);
	
	if (isReportSafe($report, false)) {
		$result++;
	}
}

fclose($file);

echo $result . PHP_EOL;

/*
	PART TWO
*/

$file = fopen('input.csv', 'r');
$result = 0;

while (($line = fgetcsv($file)) !== FALSE) {
	$isSafe = true;
	$report = explode(' ', $line[0]);
	
	if (isReportSafe($report, true)) {
		$result++;
	}
}

fclose($file);

echo $result . PHP_EOL;
