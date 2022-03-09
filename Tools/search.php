<?php

class Search {
    private string $searchString;
    private string $path;
    public int $searchedDirs = 0;
    public int $searchedFiles = 0;

    public function __construct($argv)
    {
        foreach ($argv as $index => $arg){
            if(! $index == 0){
                $this->parseArg($arg, $index);
            }
        }
        $this->doMagic();
    }

    private function parseArg($arg, $index)
    {
        if($arg === "--help") {
            $this->write("Search v1.0");
            $this->write("Usage: php search.php [PATH] [STRING] [OPTIONS]");
            $this->write("Search for a STRING in files and filenames in PATH.");
            $this->write("Outputs in which files (with line) or filenames the STRING is present.");
//            $this->write("OPTIONS:");
//            $this->write("      -e --exclude \"[folder1, folder2]\""); // v1.1?
            $this->write("To just type search put the following line in your .bashrc:");
            $this->write("alias search=\"php /path/to/search.php\"");
            exit();
        }
        if($index === 1) {
            $this->path = $arg;
        }
        if($index === 2) {
            $this->searchString = $arg;
        }
    }

    public function write($string){
        echo($string."\n");
    }

    private function doMagic()
    {
        if(empty($this->path) || empty($this->searchString)) {
            $this->write("insufficient number of arguments, PATH and STRING are required");
            $this->write("see \"php /path/to/search.php --help\" for help.");
            exit();
        }
        if(!file_exists($this->path)){
            $this->write("Directory $this->path can not be found.");
            exit();
        }
        if(is_file($this->path)){
            $this->write("$this->path is a file, try \"cat $this->path | grep '$this->searchString'\".");
            exit();
        }

        if(! str_ends_with($this->path, "/")) {
            $this->path = $this->path."/";
        }
        $startTime = microtime(true);
        recur($this->path, $this->searchString, $this);
        $endTime = microtime(true);
        $this->write("searched directories: $this->searchedDirs");
        $this->write("searched files: $this->searchedFiles");
        $time = $endTime - $startTime;
        $this->write("took $time seconds");
    }


}

function recur($path, $searchString, $search)
{
    function doStuff($path, $searchString, $search) {
        $search->searchedDirs += 1;
        $dirContent = scandir($path);
        if(empty($dirContent)) {
            return;
        }
        foreach ($dirContent as $item) {
            if ($item == "." || $item == "..") {
                continue;
            }
            // check if dir, if yes recurs
            if (is_dir($path . $item)) {
                doStuff($path . $item . "/", $searchString, $search);
            }
            // check if file, if yes read and search for string
            if (is_file($path . $item)) {
                $search->searchedFiles += 1;
                $itemFileName = $path . $item;
                if (str_contains($item, $searchString)) {
                    $search->write("Hit in filename $itemFileName");
                }
                try {
                    $handle = fopen($path . $item, "r");
                } catch (Exception $exception) {

                }
                if($handle === false || empty($handle)) {
                    continue;
                }
                $index = 1;
                while (($line = fgets($handle)) !== false) {
                    if (str_contains($line, $searchString)) {
                        $search->write("Hit in $itemFileName at line $index");
                    }
                    $index+=1;
                }
            }
        }
    }
    doStuff($path, $searchString, $search);
}

$search = new Search($argv);
?>