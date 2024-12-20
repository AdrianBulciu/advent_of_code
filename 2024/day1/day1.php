<?php

/*
	SETUP
*/

$file = fopen('input.csv', 'r');
$leftList = [];
$rightList = [];

function createHashMapCount(array &$list, $number) {
	if (!isset($list[$number])) {
		$list[$number] = 1;
	}
	else {
		$list[$number]++;
	}

	return;
}

while (($line = fgetcsv($file)) !== FALSE) {
	$line = explode("   ", $line[0]);
	createHashMapCount($leftList, $line[0]);
	createHashMapCount($rightList, $line[1]);
}

fclose($file);
ksort($leftList);
ksort($rightList);
$leftListKeys = array_keys($leftList);
$rightListKeys = array_keys($rightList);

/*
	PART ONE
*/

$rightIndex = 0;
$leftIndex = 0;
$result = 0;

while (isset($leftListKeys[$leftIndex])) {
	$leftNumber = $leftListKeys[$leftIndex];
	$rightNumber = $rightListKeys[$rightIndex];
	$result += abs($leftNumber - $rightNumber);

	if ($leftList[$leftNumber] > 1) {
		$leftList[$leftNumber]--;
	}
	else {
		$leftIndex++;
	}

	if ($rightList[$rightNumber] > 1) {
		$rightList[$rightNumber]--;
	}
	else {
		$rightIndex++;
	}
}

echo $result . PHP_EOL;

/*
	PART TWO
*/

$result = 0;
$leftIndex = 0;

while (isset($leftListKeys[$leftIndex])) {
	$leftNumber = $leftListKeys[$leftIndex];	
	$count = $rightList[$leftNumber] ?? 0;
	$result += $leftNumber * $count;
	$leftIndex++;	
}

echo $result . PHP_EOL; // 24316233
