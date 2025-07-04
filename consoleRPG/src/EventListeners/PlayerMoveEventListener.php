<?php

namespace consoleRPG\src\EventListeners;

use consoleRPG\InstanceSettings;
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
        file_put_contents(playerFilesPath . 'PLAYER_' . InstanceSettings::getPlayerName(), "$x;$y;P");
    }
}