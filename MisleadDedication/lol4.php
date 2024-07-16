<?php

include_once 'vendor/autoload.php';
include "colorConstants.php";

use Amp\Future;
use Amp\Parallel\Worker;
use Brot\BrotLineTask;
use Brot\BrotLineTaskCrazy;
use function Amp\async;
use function Amp\Parallel\Worker\workerPool;

$workerPool = workerPool();
$width = exec('tput cols')-1;
$height = exec('tput lines')-1;
$startTime = -1200;
$endTime = 1200;
$time = $startTime;
$timeAdd = 2;
$displayString = "";
$renderFrame = true;
$loopWritten = false;
define('ITERATIONS', 120);
$saveOutput = false;
define('OUT_FILE_NAME', 'brot_'.(new DateTime)->format('Y-m-d__H-i-s').'.array');
//file_put_contents(OUT_FILE_NAME, 'array(');
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

if (null !== $argv[1] && $argv[1] === 'crazy') {
    $taskClass = "Brot\BrotLineTaskCrazy";
} else {
    $taskClass = "Brot\BrotLineTask";
}

while (true) {
    $taskList = [];
    if ($time > $endTime) {$timeAdd *= -1;}
    if ($time < $startTime) {$timeAdd *= -1;}
    for ($y = 0; $y < $height; $y++) {
        $params = new stdClass();
        $params->width = $width;
        $params->y = $y;
        $params->time = $time;
        $params->height = $height;
        $params->endTime = $endTime;
        $params->iterations = ITERATIONS;
        $taskList[$y] = $workerPool->submit(new $taskClass($params));
    }
    foreach ($taskList as $task) {
        /** @var Worker\Execution $task */
        $displayString .= $task->getFuture()->await();
    }
    $hashtagColor = RED_LIGHT;
    clear();
    echo($displayString);
    $timesRendered += 1;
    $numHashtagsToWrite = floor($timesRendered / $timesToRenderForWholeLoop * $width);
    if ($numHashtagsToWrite > $width) {
        $numHashtagsToWrite = $width;
        $hashtagColor = BLUE_DARK;
    }
    $hashtags = $hashtagColor;
    $hashtags .= str_repeat('#', $numHashtagsToWrite);
    echo($hashtags);
    $displayString = "";
    $time += $timeAdd;
}

function clear() {
    // echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    system('tput cup 0 0');
}