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
        $this->cliComponent = new CliComponent();
        $this->loop();
    }

    function loop()
    {
        while (1) {
            $this->display->render();
            $this->cliComponent->getInput();
        }
    }
}