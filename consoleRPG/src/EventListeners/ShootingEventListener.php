<?php

namespace consoleRPG\src\EventListeners;

use consoleRPG\src\Event;

class ShootingEventListener implements \consoleRPG\src\EventListener
{

    public function getSupportedEvents(): array
    {
        return ['key'];
    }

    public function handleEvent(Event $event)
    {
        $key = $event->getData()['key'];
        $player = $event->getGame()->getPlayer();
        if ($key === "[A") {
            $player->shoot('up');
        }
        if ($key === "[B") {
            $player->shoot('down');
        }
        if ($key === "[C") {
            $player->shoot('right');
        }
        if ($key === "[D") {
            $player->shoot('left');
        }
    }
}