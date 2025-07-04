<?php

namespace consoleRPG\src;

use consoleRPG\InstanceSettings;
use consoleRPG\Logger;
use const consoleRPG\playerFilesPath;

class Player
{
    private int $xPos = 1;
    private int $yPos = 1;
    private string $displayString = TERM_FORE_YELLOW.'🯅';

    public function __construct(private readonly Game $game)
    {

    }

    public function getXPos(): int
    {
        return $this->xPos;
    }

    public function setXPos(int $xPos): Player
    {
        $this->xPos = $xPos;
        return $this;
    }

    public function getYPos(): int
    {
        return $this->yPos;
    }

    public function setYPos(int $yPos): Player
    {
        $this->yPos = $yPos;
        return $this;
    }

    public function getDisplayString(): string
    {
        return $this->displayString;
    }

    public function setDisplayString(string $displayString): Player
    {
        $this->displayString = $displayString;
        return $this;
    }

    private function attemptToMoveTo(int $x, int $y)
    {
        $map = $this->game->getMap();
        $targetTile = $map->getTile($x, $y);
        $debugString = "attempt to move to x:$x y:$y. Tile walkable: ".($targetTile->isWalkable()?1:0);
        Logger::debug_log($debugString);
        if ($targetTile->isWalkable()) {
            $this->xPos = $x;
            $this->yPos = $y;
            $this->game->dispatchEvent(new Event($this->game, "playerMove", ['x' => $this->xPos, 'y' => $this->yPos]));
        }
    }

    public function moveUp()
    {
        $this->attemptToMoveTo($this->getXPos(), $this->getYPos()-1);
    }

    public function moveDown()
    {
        $this->attemptToMoveTo($this->getXPos(), $this->getYPos()+1);
    }

    public function moveLeft()
    {
        $this->attemptToMoveTo($this->getXPos()-1, $this->getYPos());
    }

    public function moveRight()
    {
        $this->attemptToMoveTo($this->getXPos()+1, $this->getYPos());
    }

    public function shoot(string $direction)
    {
        $targetTileCoords = [];
        switch ($direction) {
            case 'left':
                $targetTileCoords = ['x' => $this->getXPos()-1, 'y' =>  $this->getYPos()];
                break;
            case 'right':
                $targetTileCoords = ['x' => $this->getXPos()+1, 'y' =>  $this->getYPos()];
                break;
            case 'up':
                $targetTileCoords = ['x' => $this->getXPos(), 'y' =>  $this->getYPos()-1];
                break;
            case 'down':
                $targetTileCoords = ['x' => $this->getXPos(), 'y' =>  $this->getYPos()+1];
                break;
            default:
                return false;
        }
        $targetTile = $this->game->getMap()->getTile($targetTileCoords['x'], $targetTileCoords['y']);
        if (false == $targetTile) { return false;}
        if ($targetTile->isWalkable()) {
             Logger::debug_log("trying to create shot for player ".InstanceSettings::getPlayerName());
            $filename = playerFilesPath . '/SHOT_' . InstanceSettings::getPlayerName() . '_' . microtime(true);
            $success = file_put_contents($filename, $targetTileCoords['x'].";".$targetTileCoords['y'].";S;".InstanceSettings::getPlayerName().';'.$direction);
            if (false === $success) {
                Logger::debug_log("failed creating shot for player ".InstanceSettings::getPlayerName());
            }
            InstanceSettings::addTrackedFile($filename);
            return true;
        }
        return false;
    }
}