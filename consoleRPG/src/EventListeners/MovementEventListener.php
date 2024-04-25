<?php

namespace consoleRPG\src\EventListeners;

use consoleRPG\src\Event;

class MovementEventListener implements \consoleRPG\src\EventListener
{

    public function getSupportedEvents(): array
    {
        return ['key'];
    }

    public function handleEvent(Event $event)
    {
        $key = $event->getData()['key'];
        $player = $event->getGame()->getPlayer();
        if ($key === 'w') {
            $player->moveUp();
        }
        if ($key === 'a') {
            $player->moveLeft();
        }
        if ($key === 's') {
            $player->moveDown();
        }
        if ($key === 'd') {
            $player->moveRight();
        }
    }
}