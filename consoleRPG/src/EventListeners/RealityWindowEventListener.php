<?php

namespace consoleRPG\src\EventListeners;

use consoleRPG\src\Event;
use consoleRPG\src\EventListener;

class RealityWindowEventListener implements EventListener
{

    public function getSupportedEvents(): array
    {
        return ["playerMove"];
    }

    public function handleEvent(Event $event)
    {
        $event->getGame()->getRealityWindow()->adjustTo(...$event->getData());
    }
}