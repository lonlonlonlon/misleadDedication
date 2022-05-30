<?php

namespace WortDingsda;

use Exception;
use MongoDB\Driver\Exception\CommandException;

require_once "Brain.php";

class Main
{
    private Brain $brain;
    public function __construct()
    {
        ini_set('memory_limit','0');
        set_time_limit(0);
        $handle = fopen ("php://stdin","r");
        $str = system('pwd');
        system('clear');
        write("Input Ordner: ($str/input)");
        $in = trim(fgets($handle));
        if (!$in) {
            $in = $str."/input";
        }
        if (!is_dir($in)) {
            if (!is_dir("./$in")) {
                write("$in nicht gefunden");
                exit();
            }
            $in = "./$in";
        }
        if (!str_ends_with($in, '/')) {
            $in =  $in."/";
        }
        $this->train($in);
        $this->generateCliStrat();
    }

    private function train(string $inFolder)
    {
        $fileArray = [];
        $dirContent = scandir($inFolder);
        foreach ($dirContent as $item) {
            if (!is_file($inFolder."/".$item)) {
                continue;
            }
            if ($item == '.') {
                continue;
            }
            if ($item == '..') {
                continue;
            }
            try {
                $content = file_get_contents("$inFolder/$item");
                if (gettype($content) === 'boolean') {
                    log("Error while training, unable to read file $inFolder/$item, skipping");
                    continue;
                }
                $this->trainOnFile($content);
            } catch (Exception $exception) {
                log("Error while training, reading $inFolder/$item failed with Exception:\n".$exception->getMessage()."\n".$exception->getTraceAsString());
            }
        }
    }

    private function trainOnFile(string $content)
    {
//        $lines = preg_split('/\r\n|\n|\r/', trim($content));
        $content = preg_replace('/\r\n|\n|\r/', ' ', $content);
        $content = preg_replace('/, /', ' , ', $content);
        $content = preg_replace('/\. |\./', ' . ', $content);
        $content = preg_replace('/! |!/', ' ! ', $content);
        $content = preg_replace('/\? |\?/', ' ? ', $content);
        $content = preg_replace('/ {5}/', ' ', $content);
        $content = preg_replace('/ {4}/', ' ', $content);
        $content = preg_replace('/ {3}/', ' ', $content);
        $content = preg_replace('/ {2}/', ' ', $content);
        $contentArray = preg_split('/ /', $content);
        debug(var_export($contentArray, 1));
        foreach ($contentArray as $pos => $word) {
            $this->brain->addWordInfo($word, [
                $contentArray[$pos + 1],
                $contentArray[$pos + 2],
                $contentArray[$pos + 3],
                $contentArray[$pos + 4],
                $contentArray[$pos + 5]
            ]);
        }
        // schon trainiert? xD
        exit();
    }
}
// TODO: Idee: gespräche im Raum mitschneiden und spracherkennung anbinden die gespräche als Trainingsdaten mitschneidet
$main = new Main();

/**
 * Array Structure
 * [
 *      "word" => [
 *          0 => [["word", 1], ["word", 11], ["word", 44], ["word", 88], ["word", 32], n],
 *          1 => [["word", 4], ["word", 29], ["word", 22], ["word", 7], ["word", 2], n],
 *          2 => [["word", 32], ["word", 29], ["word", 22], ["word", 7], ["word", 2], n],
 *          3 => [["word", 15], ["word", 5], ["word", 7], ["word", 8], ["word", 34], n],
 *          4 => [["word", 5], ["word", 29], ["word", 22], ["word", 7], ["word", 2], n]
 *      ]
 * ]
 * zu jedem Wort bis zu 5 worte die oft an n'ter stelle danach kommen mit ihrer Häufigkeit im bisherigen Training
 */

function write(string $text) {
    echo($text."\n");
}

function log(string $message) {
    file_put_contents("logfile.log", "$message\n", FILE_APPEND);
}

function debug(string $message) {
    file_put_contents("debugfile.log", "$message\n", FILE_APPEND);
}