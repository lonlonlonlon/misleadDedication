<?php

namespace consoleRPG;

foreach (glob(__DIR__."/src/*.php") as $filename)
{
    if (str_starts_with($filename, '.')) {
        continue;
    }
    include $filename;
}

pcntl_async_signals(true);
pcntl_signal(SIGINT, function () {
    # EXIT
    system("stty echo");
    system("tput cnorm");
    file_put_contents(__DIR__ . "/debug.log", "Execution ended " . shell_exec("date"), FILE_APPEND);
    system("stty sane");
    exit(0);
});
pcntl_signal(SIGTERM, function () {
    # EXIT
    system("stty echo");
    system("tput cnorm");
    file_put_contents(__DIR__ . "/debug.log", "Execution ended " . shell_exec("date"), FILE_APPEND);
    system("stty sane");
    exit(0);
});

class Logger {
    public static function debug_log($str) {
        file_put_contents(__DIR__."/debug.log", $str."\n", FILE_APPEND);
    }
}

file_put_contents(__DIR__."/debug.log", "Execution started ".shell_exec("date"), FILE_APPEND);

use consoleRPG\src\Game;
system("stty -echo");
system("tput civis");
stream_set_blocking(STDIN, 0);
system("stty -icanon");
$game = new Game();
