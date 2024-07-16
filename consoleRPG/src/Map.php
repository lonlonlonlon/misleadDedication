<?php

namespace consoleRPG\src;

class Map
{
    private object $legend;
    private array $map = [];

    public function __construct(string $filepath)
    {
        try {
            $fileContent = file_get_contents($filepath);
            $json = json_decode($fileContent);
            $this->legend = $json->legend;
            foreach ($this->legend as &$tileType) {
                foreach ($tileType->display as &$animationStep) {
                    foreach (COLOR_DEFS as $colName => $colValue) {
                        $animationStep = str_replace($colName, $colValue, $animationStep);
                    }
                }
            }
            $tmp = [];
            foreach ($json->map as $lineIndex => $line) {
                foreach (str_split($line) as $charIndex => $char) {
                    $tmp[$lineIndex][$charIndex] = (new Tile())->setDisplayString($this->legend->$char->display)->setWalkable($this->legend->$char->walkable);
                }
            }
            $this->map = $tmp;
        } catch (\Exception $exception) {
            echo("Failed to load Map $filepath.\n");
            throw $exception;
        }
    }

    public function getLegend(): object
    {
        return $this->legend;
    }

    public function setLegend(object $legend): Map
    {
        $this->legend = $legend;
        return $this;
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function setMap(array $map): Map
    {
        $this->map = $map;
        return $this;
    }

    public function getTile(int $x, int $y)
    {
        try {
            return $this->map[$y][$x];
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function getWidth()
    {
        return count($this->map[0]);
    }

    public function getHeight()
    {
        return count($this->map);
    }
}