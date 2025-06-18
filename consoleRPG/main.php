<?php

namespace consoleRPG;

foreach (glob("src/*.php") as $filename)
{
    if ($filename === 'src/inputListener.php') {
        continue;
    }
    include $filename;
}
const playerFilesPath = "./runtime_entities/";
pcntl_async_signals(true);
pcntl_signal(SIGINT, function () {
    if (!empty(InstanceSettings::getPlayerName())){
        unlink(playerFilesPath . 'PLAYER_' . InstanceSettings::getPlayerName());
    }
    file_put_contents(__DIR__ . "/debug.log", "Execution ended " . shell_exec("date"), FILE_APPEND);
    system("stty sane");
    exit(0);
});
pcntl_signal(SIGTERM, function () {
    if (!empty(InstanceSettings::getPlayerName())){
        unlink(playerFilesPath . 'PLAYER_' . InstanceSettings::getPlayerName());
    }
    file_put_contents(__DIR__ . "/debug.log", "Execution ended " . shell_exec("date"), FILE_APPEND);
    system("stty sane");
    exit(0);
});
//pcntl_signal(SIGKILL, get_sigint());

class Logger {
    public static function debug_log($str) {
        file_put_contents(__DIR__."/debug.log", $str."\n", FILE_APPEND);
    }
}
class InstanceSettings {
    private string $playerName = "";
    private static InstanceSettings $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getPlayerName()
    {
        return self::getInstance()->playerName;
    }
    public static function setPlayerName(string $playerName)
    {
        self::getInstance()->playerName = $playerName;
    }
}
file_put_contents(__DIR__."/debug.log", "Execution started ".shell_exec("date"), FILE_APPEND);

use consoleRPG\src\Game;
$stdIn = fopen("php://stdin", "r");

while (1) {
    echo "Name: ";
    $playerName = trim(fgets($stdIn));
    if  ($playerName && !file_exists(playerFilesPath . 'PLAYER_' . $playerName)) {
        InstanceSettings::setPlayerName($playerName);
        file_put_contents(playerFilesPath . 'PLAYER_' . InstanceSettings::getPlayerName(), '0;0;P');
        break;
    }
    system("clear");
}
stream_set_blocking(STDIN, 0);
system("stty -icanon");
// worker für input starten
//exec('bash -c "exec nohup setsid konsole -e php src/inputListener.php > /dev/null 2>&1 &"');
$game = new Game();
