<?php

$arr = [];
for ($x = 0; $x < 100; $x ++) {
    for ($y = 0; $y < 100; $y ++) {
        $arr[$x][$y] = generateRandomString();
    }
}

function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

file_put_contents(generateRandomString().".txt", var_export($arr));