<?php

class MainLoop
{
    private Display $display;
    private CliComponent $cliComponent;

    public function start()
    {
        // spielstand Scheiß, vielleicht serialisiertes Objekt deserialisieren
        $this->display = new Display();
        $this->display->loadMap(mapName: 'test/testMap2');
        $this->cliComponent = new CliComponent(fopen('php://stdin', 'r'));
        $this->loop();
    }

    function loop()
    {
        while (1) {
            $this->display->render();
            $in = $this->cliComponent->getInput();
            echo("\n$in\n");
        }
    }
}