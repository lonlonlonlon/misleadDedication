<?php

namespace consoleRPG\src;

use consoleRPG\InstanceSettings;
use consoleRPG\Logger;
use const consoleRPG\playerFilesPath;

const FRAME_TIME = 0040000;
const ANIMATION_TIME = 0250000;
class Game
{
    private Map $map;
    private array $maps;
    private Player $player;
    private bool $rerenderPicture = false;
    private float $lastRenderTime = 0.0;
    private float $lastAnimationTime = 0.0;
    private float $lastPhysicsTime = 0.0;
    private RealityWindow $realityWindow;
    private $sock;
    private $sockConnection;

    /** @var EventListener[]  */
    private array $eventListeners = [];
    public function __construct()
    {
        $this->player = new Player($this);
        // Maps laden
        foreach (scandir(InstanceSettings::getBaseDir().'/dat/maps/') as $mapFilename) {
            if (!str_ends_with($mapFilename, '.json')) {continue;}
            $this->maps[str_replace('.json', '', $mapFilename)] = new Map(InstanceSettings::getBaseDir().'/dat/maps/'.$mapFilename);
        }

        $this->map = $this->maps['test'];

        $this->realityWindow = new RealityWindow();
        $this->realityWindow->adjustTo($this->player->getXPos(), $this->player->getYPos());

        // Event handler laden
        foreach (scandir(InstanceSettings::getBaseDir().'/src/EventListeners/') as $listenerFilename) {
            if ($listenerFilename === '.' || $listenerFilename === '..') {continue;}
            include_once InstanceSettings::getBaseDir().'/src/EventListeners/'.$listenerFilename;
            $listenerClassName = 'consoleRPG\src\EventListeners\\'.preg_replace('/\.php/', '', $listenerFilename);
            $this->eventListeners[] = new $listenerClassName();
        }
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
//    private function non_block_read($fd, &$data) {
//        $read = array($fd);
//        $write = array();
//        $except = array();
//        $result = stream_select($read, $write, $except, 0);
//        if($result === false) throw new \Exception('stream_select failed');
//        if($result === 0) return false;
//        $data = stream_get_line($fd, 1);
//        return true;
//    }
    private function mainLoop()
    {
        $in = '';
        $stdIn = fopen('php://stdin', 'r');
        $sleepTime = FRAME_TIME / 3 * 2;
        while (1) {
            $in = fgetc($stdIn);
            xdebug_break();
            if (false !== $in && "" !== $in) {
                if ($in == '') {
                    $in2 = fgetc($stdIn);
                    $in3 = fgetc($stdIn);
                    $in = $in.$in2.$in3;
                }
                $this->dispatchEvent(new Event($this, 'key', ['key' => $in]));
                Logger::debug_log("Key pressed: " . $in);
            }

            if ($this->rerenderPicture) {
                system('clear');
                $this->draw();
                $this->lastRenderTime = microtime(true);
                $this->rerenderPicture = false;
            }

            if (microtime(true) > $this->lastAnimationTime + 0.25 /** sec */) {
                foreach ($this->map->getMap() as $line) {
                    foreach ($line as $tile) {
                        $tile->animationTick();
                    }
                }
                $this->lastAnimationTime = microtime(true);
            }
            if (microtime(true) > $this->lastPhysicsTime + 0.05 /** sec */) {
                $this->calculatePhysics();
                $this->lastPhysicsTime = microtime(true);
            }

            if (microtime(true) > $this->lastRenderTime + 0.05) {
                $this->rerenderPicture = true;
            }
            usleep($sleepTime);
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
        $yMax = $this->realityWindow->getBottomRightY();
        $xMax = $this->realityWindow->getBottomRightX();
        $yMin = $this->realityWindow->getTopLeftY();
        $xMin = $this->realityWindow->getTopLeftX();
        $entities = $this->parseCurrentEntities();

        /** @var Map $this->map*/
        for ($y = $yMin; $y < $yMax; $y++) {
            for ($x = $xMin; $x < $xMax; $x++) {
                /** @var Tile $tile */
                if ($x+1 > $this->map->getWidth() || $y+1 > $this->map->getHeight()) {continue;}
                $tile = $this->map->getTile($x, $y);
                if ($this->player->getYPos() == $y && $this->player->getXPos() == $x) {
                    echo substr($tile->getDisplayString(), 0, -1) . $this->player->getDisplayString();
                } elseif (key_exists("$x.$y", $entities)) {
                    $entity = $entities["$x.$y"];
                    switch ($entity['type']) {
                        case "P":
                            echo TERM_BACK_BLUE_DARK.TERM_FORE_LIGHT_RED.'P'.TERM_RESET;
                            break;
                        case "S":
                            echo TERM_BACK_RED_LIGHT.TERM_FORE_YELLOW.'+'.TERM_RESET;
                    }
//                    echo $tile->getDisplayString().TERM_RESET;
                } else {
                    echo $tile->getDisplayString().TERM_RESET;
                }
            }
            if ($y == $yMax-1) {continue;}
            echo TERM_RESET."\n";
        }
    }

    public function getRealityWindow(): RealityWindow
    {
        return $this->realityWindow;
    }

    private function parseCurrentEntities()
    {
        $entities = [];
        foreach (scandir(playerFilesPath) as $file) {
            if (str_starts_with($file, '.') || $file == 'PLAYER_'.InstanceSettings::getPlayerName()) {continue;}
            # datei auslesen und in array das beim render beachtet wird
            $props = explode(';', file_get_contents(playerFilesPath.'/'.$file));
            $entities["$props[0].$props[1]"] = ['x' => $props[0], 'y' => $props[1], 'type' =>  $props[2], 'originalProps' => $props];
        }
        return $entities;
    }

    private function calculatePhysics()
    {
        // check if player dead
        $entities = $this->parseCurrentEntities();
        foreach ($entities as $entity) {
            if ($entity['type'] == 'S') {
                Logger::debug_log($entity['originalProps'][3]);
                if ($entity['originalProps'][3] == InstanceSettings::getPlayerName()) {continue;}
                system("clear");
                echo "Your were killed by ".$entity['originalProps'][3].PHP_EOL;
                echo "Press any button to continue.\n";
                if (!empty(InstanceSettings::getPlayerName())){
                    unlink(playerFilesPath . 'PLAYER_' . InstanceSettings::getPlayerName());
                }
                InstanceSettings::cleanup();
                while (!fgetc(STDIN)) {
                    usleep(100000);
                }
                # EXIT
                system("stty echo");
                system("tput cnorm");
                file_put_contents(__DIR__ . "/debug.log", "Execution ended " . shell_exec("date"), FILE_APPEND);
                system("stty sane");
                exit(0);
            }
        }
        // tracked files auslesen und für jede die richtige aktion ausführen
        $trackedFiles = InstanceSettings::getTrackedFiles();
        foreach ($trackedFiles as $file) {
            $object = explode(';', file_get_contents($file));
            switch ($object[2]) {
                case 'P':
                    break;
                case 'S':
                    // make own shots move
                    // x;y;S;<player_name>;<direction>
                    switch ($object[4]) {
                        case 'left':
                            $targetCoords = [$object[0]-1, $object[1]];
                            break;
                        case 'right':
                            $targetCoords = [$object[0]+1, $object[1]];
                            break;
                        case 'up':
                            $targetCoords = [$object[0], $object[1]-1];
                            break;
                        case 'down':
                            $targetCoords = [$object[0], $object[1]+1];
                            break;
                    }
                    if(true == $this->isTileWalkable(...$targetCoords)) {
                        $object[0] = $targetCoords[0];
                        $object[1] = $targetCoords[1];
                        file_put_contents($file, implode(';', $object));
                    } else {
                        InstanceSettings::removeTrackedFile($file);
                        unlink($file);
                    }
                    break;
            }
        }
    }

    private function isTileWalkable($x, $y)
    {
        $targetTile = $this->map->getTile($x, $y);
        if (false == $targetTile) {
            return false;
        }
        if ($targetTile->isWalkable()) {
            return true;
        }
        return false;
    }
}