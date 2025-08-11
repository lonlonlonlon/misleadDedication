#!/usr/bin/php
<?php

namespace FractalHuntBruteForce;

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

foreach (glob(__DIR__ . "/src/*.php") as $filename)
{
    if (str_starts_with($filename, '.')) {
        continue;
    }
    include $filename;
}

use FractalHuntBruteForce\Stuff\CalcPart;

function getComponent()
{
    $componentParts = [
        '$X',
        '$Y',
        '$X',
        '$Y',
        '2',
        'pi()'
    ];
    return $componentParts[rand(0, count($componentParts) - 1)];
}

const calculationParts = [
    new CalcPart(1, 'log(A)'),
    new CalcPart(1, 'log10(A)'),
    new CalcPart(2, 'pow(A, B)'),
    new CalcPart(1, 'sqrt(A)'),
    new CalcPart(1, 'tan(A)'),
    new CalcPart(1, 'sin(A)'),
    new CalcPart(1, 'cos(A)'),
    new CalcPart(2, 'A / B'),
    new CalcPart(2, 'A * B'),
    new CalcPart(2, 'A + B'),
    new CalcPart(2, 'A - B'),
];

$numCalcParts = 8;

$calcParts = [];
for ($i = 0; $i < $numCalcParts; $i++) {
    $calcParts[] = calculationParts[rand(1, count(calculationParts) - 1)];
}
$a = 1;

/**
 * @param CalcPart[] $calcParts
 * @return void
 */
function fillInCalcParts(array $calcParts, $recLVL = 0): string
{
    if (empty($calcParts)) {
        return getComponent();
    }
    $currentPart = array_pop($calcParts);
    /** @var CalcPart $currentPart */
    if ($currentPart->getNumComponents() < 2) {
        $result = fillInTemplate($currentPart->getTemplate(), [fillInCalcParts($calcParts, $recLVL + 1)]);
//        echo "$recLVL\t\t$result\n";
        return $result;
    } else {
        for ($i = 0; $i < $currentPart->getNumComponents() - 1; $i++) {
            $comp[] = getComponent();
        }
        $comp[] = fillInCalcParts($calcParts, $recLVL + 1);
        $result = fillInTemplate($currentPart->getTemplate(), $comp);
//        echo "$recLVL\t\t$result\n";
        return $result;
    }
}

function fillInTemplate(string $template, array $comp)
{
    $name = 'A';
    foreach ($comp as $part) {
        $template = str_replace($name, $part, $template);
        $name++;
    }
    return $template;
}

$formula = fillInCalcParts($calcParts);
//echo "formula: $formula\n";

if (!empty($argv[1])) {
    $formula = $argv[1];
}

// calc image

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
file_put_contents("formulas.txt", $formula.PHP_EOL, 8);
$width = exec('tput cols')-1;
$height = exec('tput lines')-1;
$data = [];
$dispString = "";

# init min and max with value in result range
$X = 1 - $height * 100;
$Y = 1 - $width * 100;
$result = eval('return '.$formula.';');
$min = $result;
$max = $result;

for ($x = 1; $x < $height; $x++) {
    for ($y = 1; $y < $width; $y++) {
        $X = $x - $height * 100;
        $Y = $y - $width * 100;
        $result = eval('return '.$formula.';');
        if ($result > $max) {$max = $result;}
        if ($result < $min) {$min = $result;}
        $data[$x][$y] = $result;
    }
}
for ($x = 1; $x < $height; $x++) {
    for ($y = 1; $y < $width; $y++) {
        $dispString .= getChr($data[$x][$y], $min, $max);
    }
    $dispString .= "\n";
}
clear();
echo $dispString;

function clear() {
    // echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    system('tput cup 0 0');
}