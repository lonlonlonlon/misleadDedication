<?php

namespace ObjWortDings\src;

use Exception;

const LOGNAME = "debug.log";
const LIMITER_FROM = -20;
const LIMITER_TO = 20;
const LENGTH_TO_GENERATE = 500;

class Main
{
    private Brain $brain;

    public function __construct()
    {
        log("New execution: --------------------------------");
        $this->brain = new Brain();
        $this->run();
    }

    private function run()
    {
        $handle = fopen("php://stdin", "r");
        $this->prepareTrain($handle);
        $this->generate();
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
                log("Error while training, reading $inFolder/$item failed with Exception:\n",["exceptionMessage" => $exception->getMessage(), "exceptionTrace" => $exception->getTraceAsString()]);
            }
        }
    }

    private function trainOnFile(string $content)
    {
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
        $content = preg_replace('/„|"/', '', $content);
        $contentArray = preg_split('/ /', $content);
        foreach ($contentArray as $currentTextPosition => $currentTextWord) {
            // hier pro wort info an brain melden, villeicht durch Punkt getrennt kontext schnitte mache
            // und später eine auswertung an jedem Word wie geeignet es ist am satzanfang zu stehen basierend
            // darauf wie oft wörter davor stehen?

            // vielleicht einbauen, dass wenn für eine pos nach nem Word gesucht wird, dass der auch die drum herum mit nimmt?
            write($currentTextWord);
            $infoToAdd = [];
            for ($position = LIMITER_FROM; $position <= LIMITER_TO; $position += 1) {
                if ($position == 0) {
                    $infoToAdd[0] = $currentTextWord;
                    continue;
                }
                $transformedPositionContentArray = $currentTextPosition + $position;
                if ($position < 0 && $contentArray[$transformedPositionContentArray] == ".") {
                    $infoToAdd = [];
                }
                if ($position > 0 && $contentArray[$transformedPositionContentArray] == ".") {
                    break;
                }
                if ($contentArray[$transformedPositionContentArray]) {
                    $infoToAdd[$position] = $contentArray[$transformedPositionContentArray];
                }
            }
            log("infoToAdd", ["infoToAdd" => $infoToAdd]);
        }
        // schon trainiert? xD
    }

    private function generate()
    {
        $brain = $this->brain;
        $wordArray[] = $brain->getRandomWord();
        for ($positionInGeneration = 0; $positionInGeneration < LENGTH_TO_GENERATE; $positionInGeneration += 1) {
            
        }
    }
}

function log(string $message, array $additionalInfo = null) {
    file_put_contents(LOGNAME, $message."\n");
    if ($additionalInfo) {
        foreach ($additionalInfo as $key => $info) {
            file_put_contents(LOGNAME, "$key:\n".var_export($info, true)."\n");
        }
    }
    file_put_contents(LOGNAME, "\n");
}

function write(string $text)
{
    echo($text . "\n");
}

function clear()
{
    system('clear');
}