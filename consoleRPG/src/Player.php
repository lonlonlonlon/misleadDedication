<?php

namespace consoleRPG\src;

class Player
{
    private int $xPos;
    private int $yPos;
    private string $displayString;

    public function __construct(Game $game)
    {
        $game->addEventlistener();
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
}