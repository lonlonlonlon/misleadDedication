<?php

namespace consoleRPG\src;

class Event
{
    public function __construct(
        private Game $game,
        private $type,
        private $data
    ){}

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): Event
    {
        $this->game = $game;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Event
    {
        $this->type = $type;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): Event
    {
        $this->data = $data;
        return $this;
    }
}