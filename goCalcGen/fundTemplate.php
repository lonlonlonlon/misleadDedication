<?php

// tan($Y + $X + pi() * cos(tan($X * sin($X))))
// iteriert, in jedem Schritt $X = ergebnis der vorberechnung gesetzt
// iterationen (1000) visualisiert
// result musste zwischen -100 und 100 bleiben, sonst abbruch
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
function clear() {
    // echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    system('tput cup 0 0');
}
function getChr(mixed $result, mixed $min, mixed $max)
{
    $eighth = ( abs($min) + abs($max) ) / 10;
    if ($result < $eighth) {return WHITE_LIGHT." \033[0m";}
    if ($result < $eighth*2) {return WHITE_DARK." \033[0m";}
    if ($result < $eighth*3) {return YELLOW_LIGHT." \033[0m";}
    if ($result < $eighth*4) {return YELLOW_DARK." \033[0m";}
    if ($result < $eighth*5) {return GREEN_LIGHT." \033[0m";}
    if ($result < $eighth*6) {return GREEN_DARK." \033[0m";}
    if ($result < $eighth*7) {return BLUE_LIGHT." \033[0m";}
    if ($result < $eighth*8) {return BLUE_DARK." \033[0m";}
    if ($result < $eighth*9) {return BLACK_LIGHT." \033[0m";}
    return BLACK_DARK." \033[0m";
}

// tan($Y + $X + pi() * cos(tan($X * sin($X))))
// iteriert, in jedem Schritt $X = ergebnis der vorberechnung gesetzt
// iterationen visualisiert




function checkFreakoutFund1($x, $y, $iterations): int
{
    // TODO: TODO!
//    $part1 = 0;
//    $part2 = 0;
//    while ($iterations > 0 && $part2 < 2 && $part2 > -2) {
//        $part1 = cos(tan($x * sin($x)));
//        $part2 = tan($y + $x + pi() * $part1);
//        $x = $part1;
//        $y = $part2;
//        $iterations--;
//    }
    return $iterations;
}


function render($iter = 1000, $zoom = 1, $xMod = 0, $yMod = 0) {
    $width = exec('tput cols')-1;
    $height = exec('tput lines')-3;
    $data = [];
    $min = $max = checkFreakoutFund1(1, 1, $iter);
    $heightBy2 = $height / 2;
    $widthBy2 = $width / 2;

    for ($x = 1; $x < $height; $x++) {
        for ($y = 1; $y < $width; $y++) {
            $normX = ( $x - $heightBy2 ) / $heightBy2; // keeps between -1 and 1
            $normY = ( $y - $widthBy2 ) / $widthBy2; // keeps between -1 and 1
            $remain = checkFreakoutFund1(($normX * $zoom) + ($xMod / $zoom), ($normY * $zoom) + ($yMod / $zoom), $iter);
            if ($remain > $max) {$max = $remain;}
            if ($remain < $min) {$min = $remain;}
            $data[$x][$y] = $remain;
        }
    }
    $dispStr = "";
    for ($x = 1; $x < $height; $x++) {
        for ($y = 1; $y < $width; $y++) {
            $dispStr .= getChr($data[$x][$y], $min, $max);
        }
        $dispStr .= PHP_EOL;
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
            $zoom -= 0.1;
            break;
        case '-':
            $zoom += 0.1;
            break;
        case 'd':
            $yMod += 0.1 * $zoom;
            break;
        case 'a':
            $yMod -= 0.1 * $zoom;
            break;
        case 'w':
            $xMod -= 0.1 * $zoom;
            break;
        case 's':
            $xMod += 0.1 * $zoom;
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