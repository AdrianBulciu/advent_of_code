<?php

$data = file_get_contents('input.txt');
$muls = explode('mul(', $data);
$result = 0;
$mulIsEnabled = true;

function checkMulIsEnabled($value, &$mulIsEnabled) {	
	if (str_contains($value, 'do()')) {
		$mulIsEnabled = true;
	}

	if (str_contains($value, "don't()")) {
		$mulIsEnabled = false;
	}
}

foreach ($muls as $key => $value) {
	$len = strlen($value);
	checkMulIsEnabled($muls[$key - 1] ?? "", $mulIsEnabled);

	if ($len < 4 || !$mulIsEnabled) {
		continue;
	}

	$validMul = true;
	$firstNumber = '';
	$secondNumber = '';
	$checkSecondNumber = false;

	for ($i = 0; $i < strlen($value); $i++) {
		if ($value[$i] === ',' && !$checkSecondNumber) {
			$checkSecondNumber = true;
			continue;
		}

		if ($i > 2 && !$checkSecondNumber) {
			$validMul = false;
			break;
		}

		if ($checkSecondNumber && $value[$i] === ')') {
			break;
		}

		if (is_numeric($value[$i])) {
			if (!$checkSecondNumber) {
				$firstNumber .= $value[$i];
			}
			else {
				$secondNumber .= $value[$i];
			}
		}
		else {
			$validMul = false;
			break;
		}
	}

	if ($validMul) {
		$result += ((int) $firstNumber * (int) $secondNumber);
	}
}

echo $result . PHP_EOL;
