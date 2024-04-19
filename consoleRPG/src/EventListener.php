<?php

namespace consoleRPG\src;

interface EventListener
{
    public function getSupportedEvents(): array;

    public function handleEvent(Event $event);
}