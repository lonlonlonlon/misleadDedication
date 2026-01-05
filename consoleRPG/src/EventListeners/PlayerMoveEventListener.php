<?php

namespace consoleRPG\src\EventListeners;

use consoleRPG\src\Event;
use const consoleRPG\playerFilesPath;

class PlayerMoveEventListener implements \consoleRPG\src\EventListener
{

    public function getSupportedEvents(): array
    {
        return ['playerMove'];
    }

    public function handleEvent(Event $event)
    {
        $x = $event->getData()['x'];
        $y = $event->getData()['y'];
    }
}