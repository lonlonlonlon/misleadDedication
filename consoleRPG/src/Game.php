<?php

namespace consoleRPG\src;

class Game
{
    private Map $map;
    private Player $player;

    private $sock;
    private $sockConnection;

    /** @var EventListener[]  */
    private array $eventListeners = [];
    public function __construct()
    {
        // TODO: Scheiss laden von dat

        $this->prepareSocket();

        $this->mainLoop();
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    public function setMap(Map $map): Game
    {
        $this->map = $map;
        return $this;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): Game
    {
        $this->player = $player;
        return $this;
    }

    private function mainLoop()
    {
        $in = '';
        $status = false;
        while (1) {
            $status = socket_recv($this->sockConnection, $in, 1, MSG_DONTWAIT);
            if (false !== $in) {
                $this->dispatchEvent(new Event($this, 'key', ['key' => $in]));
            }
        }
    }

    private function prepareSocket()
    {
        if (($this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "socket_create() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error()) . "\n";
        }

        if (socket_bind($this->sock, '127.0.0.1', 8001) === false) {
            echo "socket_bind() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error($this->sock)) . "\n";
        }

        if (socket_listen($this->sock, 5) === false) {
            echo "socket_listen() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error($this->sock)) . "\n";
        }

        while (1) {
            $sockConnection = socket_accept($this->sock);
            if (false !== $sockConnection) {
                $this->sockConnection = $sockConnection;
                socket_set_nonblock($this->sockConnection);
                return;
            }
        }
    }

    private function dispatchEvent(Event $event)
    {
        foreach ($this->eventListeners as $eventListener) {
            if (in_array($event->getType(), $eventListener->getSupportedEvents())) {
                $eventListener->handleEvent($event);
            }
        }
    }
}