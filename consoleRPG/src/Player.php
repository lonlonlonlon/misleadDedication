<?php

namespace consoleRPG\src;

use consoleRPG\Logger;

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
}