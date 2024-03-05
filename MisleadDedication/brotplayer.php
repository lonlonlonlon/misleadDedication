<?php
system('clear');
ini_set('memory_limit', '20G');
try {
    $brotFile = eval('return '.file_get_contents($argv[1]).';');
    while (1) {
        foreach ($brotFile as $frame) {
            clear();
            echo($frame);
            usleep(10000);
        }
    }
} catch (Exception $exception) {
    echo "Brot puuuuut";
    exit(1);
}

function clear() {
    // echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    system('tput cup 0 0');
}