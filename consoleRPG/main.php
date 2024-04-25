<?php

namespace consoleRPG;

foreach (glob("src/*.php") as $filename)
{
    if ($filename === 'src/inputListener.php') {
        continue;
    }
    include $filename;
}

use consoleRPG\src\Game;

// worker für input starten
exec('bash -c "exec nohup setsid konsole -e php src/inputListener.php > /dev/null 2>&1 &"');
$game = new Game();
