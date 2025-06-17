<?php

namespace consoleRPG;

foreach (glob("src/*.php") as $filename)
{
    if ($filename === 'src/inputListener.php') {
        continue;
    }
    include $filename;
}
register_shutdown_function(function () {system("stty sane");});
stream_set_blocking(STDIN, 0);
system("stty -icanon");

use consoleRPG\src\Game;

// worker für input starten
//exec('bash -c "exec nohup setsid konsole -e php src/inputListener.php > /dev/null 2>&1 &"');
$game = new Game();

