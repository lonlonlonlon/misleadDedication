<?php

class LFify
{
    private $path;

    public function __construct($argv)
    {
        foreach ($argv as $index => $arg) {
            if (!$index == 0) {
                $this->parseArg($arg, $index);
            }
        }
        $this->doMagic();
    }

    private function parseArg($arg, $index)
    {
        if($index === 1) {
            $this->path = $arg;
        }
    }

    private function doMagic()
    {
        recur($this->path);
    }
}

function recur($path)
{
    if (!str_ends_with($path, '/')) {
        $path .= '/';
    }
    function doStuff($path) {
        @$dirContent = scandir($path);
        if(empty($dirContent)) {
            echo("error reading dir $path\n");
            return;
        }
        foreach ($dirContent as $item) {
            if ($item == "." || $item == "..") {
                continue;
            }
            // check if dir, if yes recur
            if (is_dir($path . $item)) {
                doStuff($path . $item . "/");
            }
            // check if file, if yes LFify it
            system("dos2unix $path$item");
        }
    }
    doStuff($path);
}

$lfify = new LFify($argv);
?>