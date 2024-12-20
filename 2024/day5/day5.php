<?php

$file = file_get_contents('input.txt');
$file = explode(PHP_EOL, trim($file));

$rules = [];
$prints = [];

foreach ($file as $row) {
    if (str_contains($row, '|')) {
        $rule = explode('|', $row);

        $rules["before"]["$rule[0]"][] = $rule[1];
        $rules["after"]["$rule[1]"][] = $rule[0];
    }
    else {
        $prints[] = $row;
    }
}

function validatePages($pages, $pagesCount, $fixRun = false) {
    $valid = true;
    $runAfterFix = false;

    foreach($pages as $key => $page) {
        $beforeRules = $GLOBALS['rules']['before'][$page] ?? null;
        $afterRules = $GLOBALS['rules']['after'][$page] ?? null;

        if ($key === 0) {
            if (isset($afterRules)) {
                $finds = array_intersect($pages, $afterRules);

                if (count($finds)) {
                    $valid = false;
                    array_shift($pages);
                    array_splice($pages, array_key_last($finds), 0, $page);
                    $runAfterFix = true;
                    break;
                }
            }
        }
        else if ($key === ($pagesCount - 1)) {
            if (isset($beforeRules)) {
                $finds = array_intersect($pages, $beforeRules);

                if (count($finds)) {
                    $valid = false;
                    array_pop($pages);
                    array_splice($pages, array_key_last($finds), 0, $page);
                    $runAfterFix = true;
                    break;
                }
            }
        }
        else {
            if (isset($afterRules)) {
                $finds = array_intersect(array_slice($pages, $key), $afterRules);

                if (count($finds)) {
                    $valid = false;
                    array_splice($pages, $key, 1);
                    array_splice($pages, array_key_last($finds) + $key, 0, $page);
                    $runAfterFix = true;
                    break;
                }
            }

            if (isset($beforeRules)) {
                $finds = array_intersect(array_slice($pages, 0, $key), $beforeRules);

                if (count($finds)) {
                    $valid = false;
                    var_dump($finds);die();
                    array_splice($pages, $key, 1);
                    array_splice($pages, array_key_last($finds) + $key, 0, $page);
                    $runAfterFix = true;
                    break;
                }
            }
        }
    }

    if ($runAfterFix) {
        return validatePages($pages, $pagesCount, true);
    }

    return [$valid, $fixRun, $pages];
}


$result = 0;
$resultPartTwo = 0;

foreach($prints as $print) {
    $pages = explode(",", $print);
    $pagesCount = count($pages);
    list($valid, $partTwo, $pages) = validatePages($pages, $pagesCount);

    if ($valid) {
        if ($partTwo) {
            $resultPartTwo += (int) $pages[floor($pagesCount / 2)]; 
        }
        else {
            $result += (int) $pages[floor($pagesCount / 2)];
        }
    }
}

print_r($result);
echo PHP_EOL;
print_r($resultPartTwo);





