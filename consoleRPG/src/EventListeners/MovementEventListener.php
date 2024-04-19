<?php

namespace consoleRPG\src\EventListeners;

class MovementEventListener implements \consoleRPG\src\EventListener
{

    public function getSupportedEvents(): array
    {
        return ['key'];
    }

    public function handleEvent(\consoleRPG\src\Event $event)
    {
        echo $event->getData()['key'];
    }
}