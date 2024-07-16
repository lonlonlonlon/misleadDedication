<?php

namespace consoleRPG\src;

class Game
{
    private Map $map;
    private array $maps;
    private Player $player;
    private bool $rerenderPicture = false;
    private float $lastRenderTime = 0.0;
    private float $lastAnimationTime = 0.0;
    private RealityWindow $realityWindow;
    private $sock;
    private $sockConnection;

    /** @var EventListener[]  */
    private array $eventListeners = [];
    public function __construct()
    {
        $this->player = new Player($this);
        // Maps laden
        foreach (scandir('dat/maps/') as $mapFilename) {
            if (!str_ends_with($mapFilename, '.json')) {continue;}
            $this->maps[str_replace('.json', '', $mapFilename)] = new Map('dat/maps/'.$mapFilename);
        }

        $this->map = $this->maps['test'];

        $this->realityWindow = new RealityWindow();
        $this->realityWindow->setTopLeft(0, 0);
        $this->realityWindow->setBottomRight(20, 20);

        // Event handler laden
        foreach (scandir('src/EventListeners/') as $listenerFilename) {
            if ($listenerFilename === '.' || $listenerFilename === '..') {continue;}
            include_once 'src/EventListeners/'.$listenerFilename;
            $listenerClassName = 'consoleRPG\src\EventListeners\\'.preg_replace('/\.php/', '', $listenerFilename);
            $this->eventListeners[] = new $listenerClassName();
        }

        $this->prepareSocket();

        $this->mainLoop();
    }

    public function renderFrame()
    {
        $this->rerenderPicture = true;
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
        while (1) {
            socket_recv($this->sockConnection, $in, 1, MSG_DONTWAIT);
            if (false !== $in) {
                $this->dispatchEvent(new Event($this, 'key', ['key' => $in]));
            }

            if ($this->rerenderPicture) {
                system('clear');
                $this->draw();
                $this->lastRenderTime = microtime(true);
                $this->rerenderPicture = false;
            }

            if (microtime(true) > $this->lastAnimationTime + 0.1 /** sec */) {
                foreach ($this->map->getMap() as $line) {
                    foreach ($line as $tile) {
                        $tile->animationTick();
                    }
                }
                $this->lastAnimationTime = microtime(true);
            }

            if (microtime(true) > $this->lastRenderTime + 0.01) {
                $this->rerenderPicture = true;
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

        register_shutdown_function(function () {
            socket_shutdown($this->sockConnection);
            socket_shutdown($this->sock);
            exit(0);
        });

        while (1) {
            $sockConnection = socket_accept($this->sock);
            if (false !== $sockConnection) {
                $this->sockConnection = $sockConnection;
                socket_set_nonblock($this->sockConnection);
                return;
            }
        }
    }

    public function dispatchEvent(Event $event)
    {
        foreach ($this->eventListeners as $eventListener) {
            if (in_array($event->getType(), $eventListener->getSupportedEvents())) {
                $eventListener->handleEvent($event);
            }
        }
    }

    private function draw()
    {
        /** @var Map $this->map*/
        for ($y = $this->realityWindow->getTopLeftY(); $y < $this->realityWindow->getBottomRightY(); $y++) {
            for ($x = $this->realityWindow->getTopLeftX(); $x < $this->realityWindow->getBottomRightX(); $x++) {
                /** @var Tile $tile */
                if ($x+1 > $this->map->getWidth() || $y+1 > $this->map->getHeight()) {continue;}
                $tile = $this->map->getTile($x, $y);
                if ($this->player->getYPos() == $y && $this->player->getXPos() == $x) {
                    echo substr($tile->getDisplayString(), 0, -1) . $this->player->getDisplayString();
                } else {
                    echo $tile->getDisplayString().TERM_RESET;
                }
            }
            echo TERM_RESET."\n";
        }
    }

    public function getRealityWindow(): RealityWindow
    {
        return $this->realityWindow;
    }
}