<?php

namespace consoleRPG\src;

class Tile
{
    /** @var string */
    private array $displayString = [' '];
    private $onPlayerEnter = null;
    private $animationFrame = 0;
    private bool $walkable;

    public function isWalkable(): bool
    {
        return $this->walkable;
    }

    public function setWalkable(bool $isWalkable): Tile
    {
        $this->walkable = $isWalkable;
        return $this;
    }

    public function __construct()
    {
        $this->onPlayerEnter = function () {
        };
    }

    public function animationTick()
    {
        $this->animationFrame += 1;
        if ($this->animationFrame > count($this->displayString) -1) {
            $this->animationFrame = 0;
        }
    }

    public function getDisplayString(): string
    {
        return $this->displayString[$this->animationFrame];
    }

    public function setDisplayString(array $displayString): self
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