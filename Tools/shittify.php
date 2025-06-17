<?php

$argV = $argv;
array_shift($argV);
foreach ($argV as $arg) {
    echo shittify($arg);
    echo " ";
}
echo PHP_EOL;

function shittify($arg)
{
    $chars = [];
    for ($i = 0; $i < strlen($arg); $i++) {
        $chars[] = $arg[$i];
    }
    shuffle($chars);
    return implode('', $chars);
}