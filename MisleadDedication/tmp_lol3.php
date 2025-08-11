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
$startTime = 0;
$endTime = 1000;
$time = $startTime;
$timeAdd = 100;
$displayString = "";
$frameTime = 0.05;
$lastFrameTime = microtime(true);
$renderFrame = true;
clear();

while (true) {
    if ($renderFrame){
        $width = exec('tput cols')-1;
        $height = exec('tput lines')-1;
        if ($time > $endTime) {$timeAdd *= -1;}
        if ($time < $startTime) {$timeAdd *= -1;}
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $displayString .= getIntensity($x, $y, $time, $width, $height, $endTime);
            }
            $displayString .= PHP_EOL;
        }
        $renderFrame = false;
    }

    $nowTime = microtime(true);
    if ($lastFrameTime + $frameTime < $nowTime) {
        $lastFrameTime = $nowTime;
        clear();
        echo($displayString);
        $displayString = "";
        $time += $timeAdd;
        $oldTime = $time;
        $renderFrame = true;
    }
}

function getChar(int $x, int $y, int $time, int $width, int $height, int $endTime) {
    // $iter = $x + $y + 1;
    $iter = 1000;
    $normX = $x / $width;
    $normY = $y / $height;
    $normTime = $time / $endTime;
    $brotVal = checkFreakoutFund1($normTime, - $normTime, $iter, 2, ($normX * 2.5) - 1.5, ($normY * 2) - 1);
//    $brotVal = checkFreakout(($normX * 3) - 1.5, ($normY * 2) -1, $iter, 2, (-$time/1000)+0.03, -$time/1000+0.01);
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

