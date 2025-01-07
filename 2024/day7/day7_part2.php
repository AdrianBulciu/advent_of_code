<?php

$puzzle = explode(PHP_EOL, file_get_contents('input.txt'));
$result = 0;

foreach ($puzzle as $input) {
    if (empty($input)) {
        continue;
    }

    $input = explode(':', $input);
    $answer = $input[0];
    $equation = ltrim($input[1]);
    $numbers = explode(' ', $equation);
    $stack = [
        $numbers[0]
    ];
    $index = 0;

    while (true) {
       $nextIndex = $index + 1;

       if ($nextIndex == count($numbers)) {
           break;
       }

       $number = $stack[$index] ?? null;
       $nextNumber = $numbers[$nextIndex];

       foreach ($stack as $key => $s) {
           $var1 = $s + $nextNumber;
           $var2 = $s * $nextNumber;
           $var3 = $s . $nextNumber;
           unset($stack[$key]);
           array_push($stack, $var1, $var2, $var3);

           if ($nextIndex === count($numbers) - 1) {
               if ($var1 == $answer || $var2 == $answer || $var3 == $answer) {
                   $result += $answer;
                   break;
               }
           }
       }

       $index++;
    }
}

print_r($result);
