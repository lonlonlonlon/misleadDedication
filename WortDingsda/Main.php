<?php

namespace WortDingsda;

use Exception;

require_once "Brain.php";

class Main
{
    private Brain $brain;

    public function __construct()
    {
        system("export TERM=linux");
        ini_set('memory_limit', '16G');
        set_time_limit(0);
        $this->brain = new Brain();
        $handle = fopen("php://stdin", "r");
        while (true) {
            clear();
            write("load brain? = l | train new Brain? = t (l/t)");
            $input = trim(fgets($handle));
            if ($input === "l") {
                $this->loadBrainDialouge($handle);
                break;
            } elseif ($input === "t") {
                break;
            } else {
                write("invalid input");
            }
        }

        $this->prepareTrain($handle);
        $this->generateCliStrat($handle);
    }

    public function prepareTrain($handle)
    {
        $pwd = system('pwd');
        clear();
        write("Input Ordner: ($pwd/input)");
        $in = trim(fgets($handle));
        if (!$in) {
            $in = $pwd . "/input";
        }
        if (!is_dir($in)) {
            if (!is_dir("./$in")) {
                write("$in nicht gefunden");
                exit();
            }
            $in = "./$in";
        }
        if (!str_ends_with($in, '/')) {
            $in = $in . "/";
        }
        $this->train($in);
    }

    private function train(string $inFolder)
    {
        $dirContent = scandir($inFolder);
        foreach ($dirContent as $item) {
            if (!is_file($inFolder . "/" . $item)) {
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
                log("Error while training, reading $inFolder/$item failed with Exception:\n" . $exception->getMessage() . "\n" . $exception->getTraceAsString());
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
        $content = preg_replace('/“/', '', $content);
        $content = preg_replace('/„|"/', ' ', $content);
        $contentArray = preg_split('/ /', $content);
        foreach ($contentArray as $pos => $word) {
            $infoArray = [];
            // [
            //                $contentArray[$pos + 1],
            //                $contentArray[$pos + 2],
            //                $contentArray[$pos + 3],
            //                $contentArray[$pos + 4],
            //                $contentArray[$pos + 5]
            //            ]
            if (@$contentArray[$pos + 1]) {
                $infoArray[0] = strtolower($contentArray[$pos + 1]);
                if (@$contentArray[$pos + 3]) {
                    $infoArray[1] = strtolower($contentArray[$pos + 2]);
                    if (@$contentArray[$pos + 3]) {
                        $infoArray[2] = strtolower($contentArray[$pos + 3]);
                        if (@$contentArray[$pos + 4]) {
                            $infoArray[3] = strtolower($contentArray[$pos + 4]);
                            if (@$contentArray[$pos + 5]) {
                                $infoArray[4] = strtolower($contentArray[$pos + 5]);
                            }
                        }
                    }
                }
            }
            $this->brain->addWordInfo($word, $infoArray);
        }
        // schon trainiert? xD
    }

    private function loadBrainDialouge($handle)
    {
        while (1) {
            clear();
            write("welches Brain?");
            $brains = scandir('./brains/');
            foreach ($brains as $index => $brain) {
                if ($brain === '.' || $brain === '..') {
                    unset($brains[$brain]);
                } else {
                    if (str_ends_with($brain, '.brain')) {
                        write("$brain");
                    }
                }
            }
            $input = trim(fgets($handle));
            if (is_file("./brain/$input.brain")) {
                $this->brain = $this->loadBrain("./brain/$input.brain");
                break;
            }
        }
        $this->generateCliStrat($handle);
    }

    private function loadBrain(string $pathToBrain): Brain
    {
        try {
            $brain = unserialize(file_get_contents($pathToBrain));
        } catch (Exception $exception) {
            log("hier noch son Fehler oder so: also beim hirn lesen\n" . $exception->getMessage() . "\n" . $exception->getTraceAsString());
        }
        return $brain;
    }

    private function decideBetweenWords(string $word1, int $score1, string $word2, int $score2) {
        $scoreAdd = $score1 + $score2;
        $rand = random_int(0, $scoreAdd);
        if ($rand > $score1) {
            return $word2;
        } else {
            return $word1;
        }
    }

    private function generateCliStrat($handle)
    {
        clear();
        write("press the enter to continue");
        fgets($handle);
        $brain = $this->brain;
        $text = "Hallo , wie geht es";
        $lastWords = ["hallo", ",", "wie", "geht", "es"];
        $tmp = $this->generateTextStart();
        $text = $tmp[0];
        $lastWords = $tmp[1];
        for ($n = 0; $n < 100; $n++) {
            $add = $brain->getCalculatedFollower($lastWords);
            $text .= " ".$add;
            array_shift($lastWords);
            array_push($lastWords, $add);
        }
        write($text);
    }

    private function generateTextStart()
    {
        /**
         * returns [$text, $lastWords]
         */
        $text = $this->brain->getRandomWord()." ";
        $lastWords = [trim($text)];
        $word = $this->brain->getCalculatedFollower($lastWords);
        $lastWords[] = $word;
        $text .= $word." ";
        $word = $this->brain->getCalculatedFollower($lastWords);
        $lastWords[] = $word;
        $text .= $word." ";
        $word = $this->brain->getCalculatedFollower($lastWords);
        $lastWords[] = $word;
        $text .= $word." ";
        $word = $this->brain->getCalculatedFollower($lastWords);
        $lastWords[] = $word;
        $text .= $word;
        return [$text, $lastWords];
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

function write(string $text)
{
    echo($text . "\n");
}

function log(string $message)
{
    file_put_contents("logfile.log", "$message\n", FILE_APPEND);
}

function debug(string $message)
{
    file_put_contents("debugfile.log", "$message\n", FILE_APPEND);
}

function clear()
{
    system('clear');
}