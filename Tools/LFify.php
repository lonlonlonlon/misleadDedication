<?php

class LFify
{
    private $path;

    public function __construct($argv)
    {
        var_dump($argv);
        foreach ($argv as $index => $arg) {
            if (!$index === 0) {
                $this->parseArg($arg, $index);
            }
        }
        $this->doMagic();
    }

    private function parseArg($arg, $index)
    {
        echo("$index: $arg\n");
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
    echo("$path\n");
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
            echo("LFified $path$item\n");
        }
    }
    doStuff($path);
}

$lfify = new LFify($argv);
?>