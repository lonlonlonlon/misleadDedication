<?php


include_once 'vendor/autoload.php';
include "colorConstants.php";

use Amp\Parallel\Worker;

class BrotLineTaskCrazy implements Worker\Task
{
    private object $params;

    public function __construct(object $params)
    {
        $this->params = $params;
    }
    public function run(\Amp\Sync\Channel $channel, \Amp\Cancellation $cancellation): mixed
    {
        $displayString = '';
        for ($x = 0; $x < $this->params->width; $x++) {
            $displayString .= self::getChar($x, $this->params->y, $this->params->time, $this->params->width, $this->params->height, $this->params->endTime, $this->params->iterations);
        }
        return $displayString . PHP_EOL;
    }

    static function getChar(int $x, int $y, int $time, int $width, int $height, int $endTime, int $iterations) {
        // $iter = $x + $y + 1;
        $iter = $iterations;
        $normX = $x / $width;
        $normY = $y / $height;
        $normTime = $time / $endTime;
        $brotVal = self::checkFreakout(($normX * 2.5) - 1.5, ($normY * 2) - 1,  $iter, 2, $normTime * $normX * 3 - 0.5, $normTime * $normY * 3 + 0.5);
//        $brotVal = self::checkFreakout(($normX * 2.5) - 1.5, ($normY * 2) - 1,  $iter, $iter / 2, $normTime * $normX * 3 - 0.5, $normTime * $normY * 3 + 0.5);

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

    static function checkFreakout($x, $y, $remainIter, $stopAt, $xAdd, $yAdd)
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
}