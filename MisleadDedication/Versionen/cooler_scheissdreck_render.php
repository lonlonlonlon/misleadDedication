<?php
const WHITE_DARK="\033[47m";       # White
const YELLOW_DARK="\033[43m";      # Yellow
const GREEN_DARK="\033[42m";       # Green
const CYAN_DARK="\033[46m";        # Cyan
const BLUE_DARK="\033[44m";        # Blue
const PURPLE_DARK="\033[45m";      # Purple
const RED_DARK="\033[41m";         # Red
const BLACK_DARK="\033[40m";       # Black


const BLACK_LIGHT="\033[0;100m";   # Black
const RED_LIGHT="\033[0;101m";     # Red
const GREEN_LIGHT="\033[0;102m";   # Green
const YELLOW_LIGHT="\033[0;103m";  # Yellow
const BLUE_LIGHT="\033[0;104m";    # Blue
const PURPLE_LIGHT="\033[0;105m";  # Purple
const CYAN_LIGHT="\033[0;106m";    # Cyan
const WHITE_LIGHT="\033[0;107m";   # White

$width = exec('tput cols')-1;
$height = exec('tput lines')-1;
$startTime = -1200;
$endTime = 1200;
$time = $startTime;
$timeAdd = 1;
$displayString = "";
$frameTime = 0.00001;
$lastFrameTime = microtime(true);
$renderFrame = true;
$loopWritten = false;
define('ITERATIONS', 70);
$saveOutput = false;
define('OUT_FILE_NAME', 'brot_'.(new DateTime)->format('Y-m-d__H-i-s').'.array');
file_put_contents(OUT_FILE_NAME, 'array(');
clear();
system('clear');
$timesRendered = 0;
$timesToRenderForWholeLoop = floor(($endTime - $startTime) / $timeAdd) * 2;

ini_set('memory_limit', '1G');

foreach ($argv as $index => $value) {
    if ($index == 0) {continue;}
    if ($value === 'save') {
        $saveOutput = true;
    }
}
define('SAVE_OUTPUT', $saveOutput);

while (true) {
    if ($renderFrame){
        $width = exec('tput cols')-1;
        $height = exec('tput lines')-2;
        if ($time > $endTime) {$timeAdd *= -1;}
        if ($time < $startTime) {$timeAdd *= -1;}
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $displayString .= getChar($x, $y, $time, $width, $height, $endTime);
            }
            $displayString .= PHP_EOL;
        }
        $renderFrame = false;
    }

    $nowTime = microtime(true);
    if ($lastFrameTime + $frameTime < $nowTime) {
        $hastagColor = RED_LIGHT;
        $lastFrameTime = $nowTime;
        clear();
        echo($displayString);
        $timesRendered += 1;
        if (SAVE_OUTPUT) {
            if (!($timesRendered >= $timesToRenderForWholeLoop)) {
                file_put_contents(OUT_FILE_NAME, '' . var_export($displayString, true) . ',', 8);
            } else {
                if (false === $loopWritten) {
                    $loopWritten = true;
                    file_put_contents(OUT_FILE_NAME, ')', 8);
                }
            }
            $numHashtagsToWrite = floor($timesRendered / $timesToRenderForWholeLoop * $width);
            if ($numHashtagsToWrite > $width) {
                $numHashtagsToWrite = $width;
                $hastagColor = BLUE_DARK;
            }
            $haashtags = $hastagColor;
            for ($i = 0; $i < $numHashtagsToWrite; $i++) {
                $haashtags .= '#';
            }
            echo($haashtags);
        }
        $displayString = "";
        $time += $timeAdd;
        $oldTime = $time;
        $renderFrame = true;
    }
}

function getChar(int $x, int $y, int $time, int $width, int $height, int $endTime) {
    // $iter = $x + $y + 1;
    $iter = ITERATIONS;
    $normX = $x / $width;
    $normY = $y / $height;
    $normTime = $time / $endTime;
    if ($time > 0) {
        $brotVal = checkFreakout(($normX * 2.5) - 1.5, ($normY * 2) - 1,  $iter, 2, $normTime * $normX * 3 - 0.5, $normTime * $normY * 3 + 0.5);
    } else {
        $brotVal = checkFreakout(($normX * 2.5) - 1.5, ($normY * 2) - 1,  $iter, 2, $normTime * $normY * 3 - 0.5, $normTime * $normX * 3 + 0.5);
    }
    $normBrotVal = $brotVal / $iter;
    if ($normBrotVal < 0.1) {
        return WHITE_LIGHT." \033[0m";
    }
    if ($normBrotVal < 0.2) {
        return WHITE_DARK." \033[0m";
    }
    if ($normBrotVal < 0.3) {
        return YELLOW_LIGHT." \033[0m";
    }
    if ($normBrotVal < 0.4) {
        return YELLOW_DARK." \033[0m";
    }
    if ($normBrotVal < 0.5) {
        return GREEN_LIGHT." \033[0m";
    }
    if ($normBrotVal < 0.6) {
        return GREEN_DARK." \033[0m";
    }
    if ($normBrotVal < 0.7) {
        return BLUE_LIGHT." \033[0m";
    }
    if ($normBrotVal < 0.8) {
        return BLUE_DARK." \033[0m";
    }
    if ($normBrotVal < 0.9) {
        return BLACK_LIGHT." \033[0m";
    }
    return BLACK_DARK." \033[0m";
}

function clear() {
    // echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    system('tput cup 0 0');
}

function checkFreakout($x, $y, $remainIter, $stopAt, $xAdd, $yAdd)
{
    $xx = $x * $x;
    $yy = $y * $y;
    $xy = $x * $y;
    $woIchGradBin = $xx + $yy;
    while ($woIchGradBin <= $stopAt && $remainIter > 0) {
        $x = $xx - $yy + $xAdd;
        $y = $xy + $xy + $yAdd;
        $xx = $x * $x;
        $yy = $y * $y;
        $xy = $x * $y;
        $woIchGradBin = $xx + $yy;
        $remainIter -= 1;
    }
    return $remainIter;
}

