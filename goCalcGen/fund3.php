<?php

// tan($Y + $X + pi() * cos(tan($X * sin($X))))
// iteriert, in jedem Schritt $X = ergebnis der vorberechnung gesetzt
// iterationen (1000) visualisiert
// result musste zwischen -100 und 100 bleiben, sonst abbruch
include_once "src/colorDef.php";
$GLOBALS['numBackCols'] = count(BACKGROUND_COLORS);
$GLOBALS['numForeCols'] = count(FOREGROUND_COLORS);

system("clear");
pcntl_async_signals(true);
pcntl_signal(SIGINT, function () {
    # EXIT
    system("stty echo");
    system("tput cnorm");
    system("stty sane");
    exit(0);
});
pcntl_signal(SIGTERM, function () {
    # EXIT
    system("stty echo");
    system("tput cnorm");
    system("stty sane");
    exit(0);
});

system("stty -echo");
system("tput civis");
stream_set_blocking(STDIN, 0);
system("stty -icanon");

const halfCharDown = "▄";
const halfCharUp = "▀";
function clear() {
    // echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    system('tput cup 0 0');
}
function getChr(mixed $result, mixed $min, mixed $max, $secondResult)
{
    $threshold1 = ( abs($min) + abs($max) ) / $GLOBALS['numBackCols'];
    foreach (BACKGROUND_COLORS as $i => $color) {
        if ($result < $i * $threshold1) {
            $first = $color;
            break;
        }
    }
    $threshold2 = ( abs($min) + abs($max) ) / $GLOBALS['numForeCols'];
    foreach (FOREGROUND_COLORS as $i => $color) {
        if ($secondResult < $i * $threshold2) {
            $second = $color;
            break;
        }
    }
    if (empty($first)) {
        $first = BACKGROUND_COLORS[$GLOBALS['numBackCols'] - 1];
    }
    if (empty($second)) {
        $second = FOREGROUND_COLORS[$GLOBALS['numForeCols'] - 1];
    }
    return "\e[".$second . ';' . $first . 'm' . halfCharDown;
}

// tan($Y + $X + pi() * cos(tan($X * sin($X))))
// iteriert, in jedem Schritt $X = ergebnis der vorberechnung gesetzt
// iterationen visualisiert




function checkFreakoutFund1($xAdd, $yAdd, $iterations, $x = 0, $y = 0): int
{
    $xx = $x * $x;
    $yy = $y * $y;
    $xy = $x * $y;
    $woIchGradBin = $xx + $yy;
    while ($woIchGradBin <= 2 && $iterations > 0) {
        $x = $xx - $yy + $xAdd;
        $y = $xy + $xy + $yAdd;
        $xx = $x * $x;
        $yy = $y * $y;
        $xy = $x * $y;
        $woIchGradBin = $xx + $yy;
        $iterations -= 1;
    }
    return $iterations;
}


function render($iter = 1000, $zoom = 1, $xMod = 0, $yMod = 0) {
    $width = exec('tput cols')-1;
    $height = 2 *exec('tput lines');
    $height -= 8;
    $data = [];
    $min = $max = checkFreakoutFund1(1, 1, $iter);
    $heightBy2 = $height / 2;
    $widthBy2 = $width / 2;

    for ($x = 1; $x < $width; $x++) {
        for ($y = 1; $y < $height; $y++) {
            $normX = ( $x - $widthBy2 ) / $widthBy2; // keeps between -1 and 1
            $normY = ( $y - $heightBy2 ) / $heightBy2; // keeps between -1 and 1
            $remain = checkFreakoutFund1(($normX * $zoom) + $xMod, ($normY * $zoom) + $yMod, $iter);
//            $remain = checkFreakoutFund1(($normX * $zoom) + ($xMod / $zoom), ($normY * $zoom) + ($yMod / $zoom), $iter);
            if ($remain > $max) {$max = $remain;}
            if ($remain < $min) {$min = $remain;}
            $data[$x][$y] = $remain;
        }
    }
    $dispStr = "";
    for ($y = 1; $y < $height; $y+=2) {
        for ($x = 1; $x < $width; $x++) {
            $secondResult = null;
            try{$secondResult = $data[$x][$y+1];} catch (Throwable) {}
            $dispStr .= getChr($data[$x][$y], $min, $max, $secondResult);
            if ($y+2 > $height) {
                break;
            }
        }
        $dispStr .= TERM_RESET.PHP_EOL;
    }
    system("clear");
    echo $dispStr;
}

$iter = 100;
$zoom = 1; // zoom-- = reinzoomen; zoom++ = rauszoomen
$xMod = 0;
$yMod = 0;
$stdIn = fopen('php://stdin', 'r');
while (1) {
    render($iter, $zoom, $xMod, $yMod);
    echo "[+] = zoom in 10%   [-] = zoom out 10%   [d] = move right 10%  |  iter: $iter  zoom: $zoom\n";
    echo "[w] = move up 10%   [a] = move left 10%  [s] = move down 10%   |  xMod: $xMod  yMod: $yMod\n";
    echo "[1] = iter += 10%   [2] = iter -= 10%\n";
    while (empty($in)) {
        $in = fgetc($stdIn);
        usleep(100);
    }
    switch ($in) {
        case '+':
            $zoom -= $zoom / 10;
            break;
        case '-':
            $zoom += $zoom / 10;
            break;
        case 'd':
            $xMod += 0.1 * $zoom;
            break;
        case 'a':
            $xMod -= 0.1 * $zoom;
            break;
        case 'w':
            $yMod -= 0.1 * $zoom;
            break;
        case 's':
            $yMod += 0.1 * $zoom;
            break;
        case '1':
            $iter += $iter / 10;
            break;
        case '2':
            $iter -= $iter / 10;
            break;
    }
    unset($in);
    clear();
    echo "RENDERING";
}