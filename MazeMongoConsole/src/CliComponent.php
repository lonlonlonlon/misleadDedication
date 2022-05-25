<?php

class CliComponent
{
    /**
     * @var resource
     */
    private $stdin;

    public function __construct($stream)
    {
        system("stty -icanon");
        try {
            $this->stdin = fopen('php://stdin', 'r');
        } catch (Exception $exception) {
            StaticTool::fatalError("cant open stdin");
        }
    }

    public function getInput(): string
    {
        $result = fread($this->stdin, 1);
        if (gettype($result) == 'boolean') {
            StaticTool::fatalError("got false from stdin fgetc");
        }
        return $result;
    }
}

class InputWorker extends Worker {

}