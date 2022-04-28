<?php

class Display
{

    public function render()
    {
    }

    public function loadMap(string $mapName)
    {
        try {
            echo(system('pwd'));
            @$picContent = file_get_contents("resources/maps/$mapName.pic");
            @$jsonContent = file_get_contents("resources/maps/$mapName.json");
        } catch (Exception $exception) {
            // dunnow, maybee error handler klasse oder sou?
            echo("fatal Error du Honk\n");
        }

    }
}