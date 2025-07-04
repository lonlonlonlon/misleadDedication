<?php

$words = [];
$decidedWords = [];

foreach (scandir('words') as $filename) {
    if ($filename == '.' || $filename == '..') {
        continue;
    }
    $handle = fopen('words/' . $filename, 'r');
    while (($line = fgets($handle)) !== false) {
        $words[$filename][] = trim($line);
    }
}

$template = "\t\t" . file_get_contents('template');

function getWord(string $currentWord, string $currentNumber, array &$words, &$decidedWords)
{
    if (empty($decidedWords[$currentWord.$currentNumber])) {
        if (count($words[$currentWord]) < 1) {
            echo "brauche mehr $currentWord.\n";
            exit(1);
        }
        $rnd = random_int(0, count($words[$currentWord])-1);
        $decidedWords[$currentWord.$currentNumber] = $words[$currentWord][$rnd];
        unset($words[$currentWord][$rnd]);
        sort($words[$currentWord]);
        return $decidedWords[$currentWord.$currentNumber];
    } else {
        return $decidedWords[$currentWord.$currentNumber];
    }
}

$template = explode('$', $template);
$finishedString = "";
$currentWord = "";
foreach ($template as $index => $part) {
    $currentPart = $index % 3;
    // 0 = text
    // 1 = word
    // 2 = number
    switch ($currentPart) {
        case 0:
            $finishedString .= $part;
            break;
        case 1:
            $currentWord = $part;
            break;
        case 2:
            $currentNumber = $part;
            $finishedString .= getWord($currentWord, $currentNumber, $words, $decidedWords);
            break;
    }
}

echo trim($finishedString).PHP_EOL;