<?php

$puzzle = explode(PHP_EOL, file_get_contents('input.txt'));
$correctEquations = [];

foreach ($puzzle as $input) {
    if (empty($input)) {
        continue;
    }

    $input = explode(':', $input);
    $answer = $input[0];
    $equation = ltrim($input[1]);
    $numbers = explode(' ', $equation);
    $positions = count($numbers) - 1;
    $possibilities = pow(2, $positions);
    $attempts = [];

    for ($i = 0; $i < $positions; $i++) {
        $reference = $possibilities / pow(2, $i);
        $iterations = $possibilities / $reference;
        $invert = true;
        $nextLimit = $reference;

        for ($j = 0; $j < $possibilities; $j++) {
            if ($i == 0) {
                $attempts[0][] = $j % 2 == 0 ? '+' : '*';
                continue;
            }

            if ($j >= $nextLimit) {
                $invert = !$invert;
                $nextLimit += $reference;
            }

            if ($invert) {
                $attempts[$i][] = $attempts[$i - 1][$j] == '+' ? '*' : '+';
            }
            else {
                $attempts[$i][] = $attempts[$i - 1][$j];
            }
        }
    }

    for ($j = 0; $j < $possibilities; $j++) {
        $result = $numbers[0];

        for ($i = 0; $i < $positions; $i++) {
            if ($attempts[$i][$j] === '+') {
                $result += $numbers[$i + 1];
            }
            else {
                $result *= $numbers[$i + 1];
            }

            if ($result > $answer) {
                break;
            }
        }

        if ($result == (int) $answer) {
            $correctEquations[] = $answer;
            break;
        }
    }
}

print_r(array_sum($correctEquations));
