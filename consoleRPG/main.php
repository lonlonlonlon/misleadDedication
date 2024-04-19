<?php

namespace consoleRPG;

foreach (glob("src/*.php") as $filename)
{
    if ($filename === 'src/inputListener.php') {
        continue;
    }
    include $filename;
}

// konsole -e command
use consoleRPG\src\Game;

exec('konsole -e php src/inputListener.php > /dev/null &');

$game = new Game();
