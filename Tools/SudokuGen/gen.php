<?php

namespace Sudoku;


function getEmptyGrid(): array
{
    for ($x = 0; $x < 9; $x++) {
        for ($y = 0; $y < 9; $y++) {
            $grid[$x][$y] = null;
        }
    }
    return $grid;
}

function getRandValArray(): array
{
    $arr = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    shuffle($arr);
    return $arr;
}

function valInRow(int $x, int $val, $grid): bool
{
    if (!validateCoord($x) || !validateValue($val)) {
        throw new \Exception("Invalid value in [x:$x, val:$val]");
    }
    foreach ($grid[$x] as $value) {
        if ($value == $val) {
            return true;
        }
    }
    return false;
}

function valInCol(int $y, int $val, $grid): bool
{
    if (!validateCoord($y) || !validateValue($val)) {
        throw new \Exception("Invalid value in [y:$y, val:$val]");
    }
    foreach ($grid as $row) {
        foreach ($row as $yIndex => $value) {
            if ($yIndex == $y && $val == $value) {
                return true;
            }
        }
    }
    return false;
}

function valInBox($x, $y, $val, $grid): bool
{
    if (!validateCoord($x) || !validateCoord($y) || !validateValue($val)) {
        throw new \Exception("Invalid value in [x:$x, y:$y, val:$val]");
    }
    $boxX = floor($x / 3);
    $boxY = floor($y / 3);
    foreach ($grid as $xGrid => $row) {
        foreach ($row as $yGrid => $value) {
            if (
                floor($xGrid / 3) == $boxX
                && floor($yGrid / 3) == $boxY
                && $val == $value
            ) {
                return true;
            }
        }
    }
    return false;
}

function validateCoord(int $coord): bool
{
    if ($coord < 9 && $coord >= 0) {
        return true;
    }
    return false;
}

function validateValue(int $val): bool
{
    if ($val <= 9 && $val >= 1) {
        return true;
    }
    return false;
}

function toCsv(array $grid): string
{
    $csvString = "";
    foreach ($grid as $xIndex => $row) {
        foreach ($row as $yIndex => $value) {
            if (empty($value)) {
                $value = 'N';
            }
            $csvString .= $value;
            if ($yIndex == count($row) - 1) {
                $csvString .= PHP_EOL;
            } else {
                $csvString .= ';';
            }
        }
    }
    return $csvString;
}

function generate(string $format) {
    $grid = getEmptyGrid();
    $grid = genRecur(0, 0, $grid);
    if (strtolower($format) == 'csv') {
        return toCsv($grid);
    }
    return "unsupported output format";
}

function genRecur($x, $y, $grid) {
    $couldBePlaced = false;
    foreach (getRandValArray() as $possibleValue) {
        if (canBePlaced($x, $y, $possibleValue, $grid)) {
            $couldBePlaced = true;
            $grid[$x][$y] = $possibleValue;
            $newY = $y;
            $newX = $x;
            if ($y == 8) {
                $newY = 0;
                $newX += 1;
                if ($newX == 9) {
                    return $grid;
                }
            } else {
                $newY += 1;
            }
            $newGrid = genRecur($newX, $newY, $grid);
            if (gettype($newGrid) == 'array') {
                $grid = $newGrid;
            } else {
                $couldBePlaced = false;
                continue;
            }
        }
    }
    if ($couldBePlaced) {
        return $grid;
    } else {
        return false;
    }
}

function canBePlaced($x, $y, $val, $grid): bool
{
    if (
        !valInRow($x, $val, $grid) &&
        !valInCol($y, $val, $grid) &&
        !valInBox($x, $y, $val, $grid)
    ) {
        return true;
    }
    return false;
}

function getPossibleSolutions($grid): array
{
    return solRecur($grid, []);
}

function solRecur($grid, array $array): bool|array
{
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $value) {
            if (empty($value)) {
                $rndVals = getRandValArray();
            }
        }
    }
}

echo(generate('csv'));



//@mkdir('out');
//for ($i = 0; $i < 1000; $i++) {
//    $time = microtime(true);
//    file_put_contents('out/'.str_replace(['.', ','], '', $time).'.csv', generate('csv'));
//}