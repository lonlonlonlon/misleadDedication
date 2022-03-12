<?php

const HIT = "H_i_t!_";
const EXCLUDED = "E_x_c_l!u_d!e__d";
const ERROR_READING = "E_r_r!o!r_R-e_a!!diN_g";
const FILE = "F__!_i_!L_E!!";
const FILENAME = "F_!!!_i_!L_E!!N__A!ME_";
const PINK = "!!w_!h__!i!_!__t!_!_e_!!";
const CLEAR_COLOR = "!__c__!lear__C!!ol___o_!R";
const CYAN = "!__!c__Y!!A-n";

class Search {
    private string $searchString;
    private string $path;
    public int $searchedDirs = 0;
    public int $searchedFiles = 0;
    private bool $excludeArgIncoming = false;
    public array $toExclude = [];
    private bool $useColor = false;
    public bool $caseSensitive = true;


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
        if($this->excludeArgIncoming) {
            $this->parseExcludeArg($arg);
            return;
        }

        if($arg === "--help" || $arg == "-h") {
            $this->write("Search v1.1");
            $this->write("Usage: php search.php [PATH] [STRING] [OPTIONS]");
            $this->write("Search for a STRING in files and filenames in PATH.");
            $this->write("Outputs in which files (with line) or filenames the STRING is present.");
            $this->write("");
            $this->write("OPTIONS:");
            $this->write("-e --exclude \"folder1, folder2, file1, file2\"");
            $this->write("\tto exclude ALL files with specific name use filename.extension");
            $this->write("\tto exclude ONE SPECIFIC file use the relative path (./folder/file.extension) originating in PATH");
            $this->write("\tto exclude a SPECIFIC folder use the relative path (./folder1/folder2) originating in PATH");
            $this->write("-c --color use colors to highlight results");
            $this->write("-i --insensitive use case insensitive search");
            $this->write("");
            $this->write("To just type search put the following line in your .bashrc:");
            $this->write("alias search=\"php /path/to/search.php\"");
            exit();
        }
        if($index === 1) {
            $this->path = $arg;
            return;
        }
        if($index === 2) {
            $this->searchString = $arg;
            return;
        }

        if($index > 2) {
            if($arg === "-e" || $arg === "--exclude") {
                $this->excludeArgIncoming = true;
            }
            if($arg === "-c" || $arg === "--color") {
                $this->useColor = true;
            }
            if($arg === "-i" || $arg === "--insensitive") {
                $this->caseSensitive = false;
            }
            return;
        }
    }

    public function write($string){
        if(!$this->useColor) {
            $string = preg_replace("/" . HIT . "/", "Hit", $string);
            $string = preg_replace("/" . EXCLUDED . "/", "Excluded", $string);
            $string = preg_replace("/" . ERROR_READING . "/", "Error Reading", $string);
            $string = preg_replace("/" . FILE . "/", "file", $string);
            $string = preg_replace("/" . FILENAME . "/", "filename", $string);
            $string = preg_replace("/" . CYAN . "/", "", $string);
            $string = preg_replace("/" . PINK . "/", "", $string);
            $string = preg_replace("/" . CLEAR_COLOR . "/", "", $string);
            echo($string . "\n");
        } else {
            $string = preg_replace("/" . HIT . "/", "\033[0;32mHit\033[0m", $string);
            $string = preg_replace("/" . EXCLUDED . "/", "\033[0;33mExcluded\033[0m", $string);
            $string = preg_replace("/" . ERROR_READING . "/", "\033[0;31mError Reading\033[0m", $string);
            $string = preg_replace("/" . FILE . "/", "\033[0;36mfile\033[0m", $string);
            $string = preg_replace("/" . FILENAME . "/", "\033[0;35mfilename\033[0m", $string);
            $string = preg_replace("/" . CYAN . "/", "\033[0;96m", $string);
            $string = preg_replace("/" . PINK . "/", "\033[0;95m", $string);
            $string = preg_replace("/" . CLEAR_COLOR . "/", "\033[0m", $string);
            echo("$string\n");
        }
    }

    private function doMagic()
    {
        if(empty($this->path) || empty($this->searchString)) {
            $this->write("insufficient number of arguments, PATH and STRING are required");
            $this->write("see \"php /path/to/search.php --help\" for help.");
            exit();
        }
        if(!file_exists($this->path)){
            $this->write(ERROR_READING . " $this->path : can not be found.");
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

    private function parseExcludeArg($arg) // TODO: noch einbauen wenn angabe zu relativem pfad dass / am ende entfernt wird wenn vorhanden
    {
        $toExcludeTmp = explode(",", $arg);
        if(count($toExcludeTmp) == 0) {
            return;
        }
        if(count($toExcludeTmp) >= 2){
            $toExcludeTmp[0] = trim($toExcludeTmp[0], " [({");
            $toExcludeTmp[count($toExcludeTmp) - 1] = trim($toExcludeTmp[count($toExcludeTmp) - 1], " ])}");
            foreach ($toExcludeTmp as $index => $item) {
                $toExcludeTmp[$index] = trim($item, " ");
            }
        } else {
            // count($toExclude) == 1
            $toExcludeTmp[0] = trim($toExcludeTmp[0], " [](){}");
        }
        $this->toExclude = $toExcludeTmp;
    }


}

function recur($path, $searchString, $search)
{
    function doStuff($path, $searchString, $search) {
        /** @var Search $search */
        $search->searchedDirs += 1;
        @$dirContent = scandir($path);
        if(empty($dirContent)) {
            $search->write(ERROR_READING . " $path");
            return;
        }
        foreach ($dirContent as $item) {
            if ($item == "." || $item == "..") {
                continue;
            }
            // check if dir, if yes recur
            if (is_dir($path . $item)) {
                if(in_array($path . $item, $search->toExclude)) {
                    // skip excluded dirs
                    $search->write(EXCLUDED . " $path$item");
                    continue;
                }
                doStuff($path . $item . "/", $searchString, $search);
            }
            // check if file, if yes read and search for string
            if (is_file($path . $item)) {
                if(in_array($item, $search->toExclude)) {
                    // skip excluded files
                    $search->write(EXCLUDED . " $item");
                    continue;
                }
                if(in_array($path . $item, $search->toExclude)) {
                    // skip excluded files
                    $search->write(EXCLUDED . " $path$item");
                    continue;
                }
                $search->searchedFiles += 1;
                $itemFileName = $path . $item;
                if ($search->caseSensitive) {
                    if (str_contains($item, $searchString)) {
                        $search->write(HIT . " in " . FILENAME . PINK . " $itemFileName" . CLEAR_COLOR);
                    }
                } else {
                    if(stripos($item, $searchString)) {
                        $search->write(HIT . " in " . FILENAME . PINK . " $itemFileName" . CLEAR_COLOR);
                    }
                }
                try {
                    @$handle = fopen($path . $item, "r");
                } catch (Exception $exception) {

                }
                if($handle === false || empty($handle)) {
                    $search->write(ERROR_READING . " $path$item");
                    continue;
                }
                $index = 1;
                while (($line = fgets($handle)) !== false) {
                    if($search->caseSensitive) {
                        if (str_contains($line, $searchString)) {
                            $search->write(HIT . " in " . FILE . PINK . " $itemFileName " . CLEAR_COLOR . "at line " . CYAN . "$index" . CLEAR_COLOR);
                        }
                    } else {
                        if (stripos($line, $searchString)) {
                            $search->write(HIT . " in " . FILE . PINK . " $itemFileName " . CLEAR_COLOR . "at line " . CYAN . "$index" . CLEAR_COLOR);
                        }
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