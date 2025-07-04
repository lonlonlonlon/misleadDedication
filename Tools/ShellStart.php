<?php

const TERM_RESET = "\033[0m";
const TERM_FORE_LIGHT_GREEN="\e[1;32m";
const TERM_FORE_LIGHT_GRAY="\e[0;37m";
const TERM_FORE_LIGHT_CYAN="\e[1;36m";
const TERM_FORE_BLUE="\e[0;34m";

echo TERM_FORE_LIGHT_CYAN.'Docker Containers running:'.TERM_FORE_BLUE.PHP_EOL;
echo shell_exec('docker ps --format \'{{ .ID }}\t{{ .Names }}\'');
echo TERM_RESET.PHP_EOL;

try {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://zenquotes.io/api/today");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = json_decode(curl_exec($curl), true)[0];
    curl_close($curl);
    echo(TERM_FORE_LIGHT_GREEN.$output['q'].TERM_FORE_LIGHT_GRAY.PHP_EOL."\t".'~ '.$output['a'].TERM_RESET.PHP_EOL);
} catch (Exception $e) {
    echo "Zitat des Tages konnte nicht abgerufen werden.";
}

echo "Ich bin Leon\n";
echo "und ich bin Jacky\n";
echo "Denk an die Tulpen!\n";
echo strtotime("12.09.1997 08:00:00").PHP_EOL;
