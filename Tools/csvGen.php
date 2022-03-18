<?php

class csvGen
{
    public function __construct($argv)
    {
        foreach ($argv as $index => $arg) {
            $this->parseArg($arg, $index);
        }

        $this->doMagic();
    }

    private function parseArg(string $arg, int $index)
    {
        if ($arg === "--help" || $arg === "-h") {
            $this->help();
        }
    }

    private function help()
    {
        $this->write("csv-gen v1.0");
        $this->write("Usage: php csvGen.php \"column1:type[:length], column2:type[:length]\" [OPTIONS]");
        $this->write("Generate a csv.");
        $this->write("");
        $this->write("OPTIONS:");
        $this->write("-f --file FILENAME");
        $this->write("\twrite to file called FILENAME");
        $this->write("-h --header");
        $this->write("\tdont write a header");
        $this->write("");
        $this->write("Example:");
        $this->write("\tphp /path/to/csvGen.php \"id:incInt:5, \" -f output.csv"); // die typen in module auslagern (ohne core-codeänderungen erweiterbar)
        $this->write(""); // villeciht noch RGB modul in statisch?
        $this->write("To just type csv-gen put the following line in your .bashrc:");
        $this->write("alias csv-gen=\"php /path/to/csvGen.php\"");
        exit();
    }

    public function write($string)
    {
        echo($string . "\n");
    }
}

$csvGen = new csvGen($argv);