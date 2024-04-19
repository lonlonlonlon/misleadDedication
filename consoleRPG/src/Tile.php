<?php

namespace consoleRPG\src;

class Tile
{
    /** @var string */
    private string $displayString = ' ';
    private $onPlayerEnter = null;

    public function __construct()
    {
        $this->onPlayerEnter = function () {
        };
    }

    public function getFisplayString(): string
    {
        return $this->displayString;
    }

    public function setDisplayString(string $displayString): self
    {
        $this->displayString = $displayString;
        return $this;
    }

    public function setOnPlayerEnter($onPlayerEnter)
    {
        $this->onPlayerEnter = $onPlayerEnter;
        return $this;
    }

    public function onPlayerEnter(Game $game)
    {
        $this->onPlayerEnter($game);
    }
}