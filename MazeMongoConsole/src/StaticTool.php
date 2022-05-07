<?php

use JetBrains\PhpStorm\NoReturn;

class StaticTool
{

    #[NoReturn] public static function fatalError(string $message, array $info = [])
    {
        system('clear');
        echo($message);
        if ($info) {
            var_dump($info);
        }
        exit();

    }
}