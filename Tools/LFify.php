<?php

class LFify
{
    public function __construct($argv)
    {
        foreach ($argv as $index => $arg) {
            if (!$index === 0) {
                $this->parseArg($arg, $index);
            }
        }
    }

    private function parseArg($arg, $index)
    {
        if($index === 1) {
            $this->path = $arg;
        } else {
            // andere optionen
        }
    }
}

$lfify = new LFify($argv);
?>