<?php

namespace consoleRPG;

foreach (glob(__DIR__."/src/*.php") as $filename)
{
    if (str_starts_with($filename, '.')) {
        continue;
    }
    include $filename;
}
const playerFilesPath = "/tmp/runtime_entities";
if (!is_dir(playerFilesPath)) {
    mkdir(playerFilesPath);
}
//const playerFilesPath = __DIR__."/runtime_entities/";
pcntl_async_signals(true);
pcntl_signal(SIGINT, function () {
    # EXIT
    if (!empty(InstanceSettings::getPlayerName())){
        unlink(playerFilesPath . 'PLAYER_' . InstanceSettings::getPlayerName());
    }
    InstanceSettings::cleanup();
    system("stty echo");
    system("tput cnorm");
    file_put_contents(__DIR__ . "/debug.log", "Execution ended " . shell_exec("date"), FILE_APPEND);
    system("stty sane");
    exit(0);
});
pcntl_signal(SIGTERM, function () {
    # EXIT
    if (!empty(InstanceSettings::getPlayerName())){
        unlink(playerFilesPath . 'PLAYER_' . InstanceSettings::getPlayerName());
    }
    InstanceSettings::cleanup();
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
class InstanceSettings {
    private string $playerName = "";
    private static InstanceSettings $instance;
    private array $trackedFiles = [];

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getBaseDir() {
        return __DIR__;
    }

    public static function getPlayerName()
    {
        return self::getInstance()->playerName;
    }
    public static function setPlayerName(string $playerName)
    {
        self::getInstance()->playerName = $playerName;
    }

    public static function addTrackedFile(string $filename)
    {
        self::getInstance()->trackedFiles[$filename] = $filename;
    }
    public static function removeTrackedFile(string $filename) {
//        unset(self::getInstance()->trackedFiles[$filename]);
//        sort(self::getInstance()->trackedFiles);
        self::getInstance()->trackedFiles = array_filter(self::getInstance()->trackedFiles,  function($element) use($filename) {
            return $element != $filename;
        });
    }
    public static function getTrackedFiles() {
        return self::getInstance()->trackedFiles;
    }

    public static function cleanup()
    {
        foreach (self::getInstance()->trackedFiles as $filename) {
            unlink($filename);
        }
    }
}
file_put_contents(__DIR__."/debug.log", "Execution started ".shell_exec("date"), FILE_APPEND);

use consoleRPG\src\Game;use mysql_xdevapi\Exception;
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
system("stty -echo");
system("tput civis");
stream_set_blocking(STDIN, 0);
system("stty -icanon");
$game = new Game();
