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
    foreach ($grid[$x] as $value) {
        if ($value == $val) {
            return true;
        }
    }
    return false;
}

function valInCol(int $y, int $val, $grid): bool
{
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
                $value = ' ';
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

function generateFull(string $format)
{
    $grid = getEmptyGrid();
    $grid = genFullRecur(0, 0, $grid);
    if (strtolower($format) == 'csv') {
        return toCsv($grid);
    }
    if (strtolower($format) == 'json') {
        return json_encode($grid);
    }
    if (strtolower($format) == 'php') {
        return $grid;
    }
    return "unsupported output format";
}

function genFullRecur($x, $y, $grid)
{
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
            $newGrid = genFullRecur($newX, $newY, $grid);
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

function getPossibleSolutions($grid, $coreCount): bool|array
{
    return solRecur($grid, [], [[], false]);
}

function solRecur($grid, array $array, $threading = [false, false]): bool|array
{
    [$workerPool, $nextWorker] = $threading;
    $promises = [];
    if (isFilled($grid)) {
        $hash = sha1(implode_r('_', $grid));
        $array[$hash] = $grid; // TODO: hier weitermachen
        return $array;
    }
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $value) {
            if (empty($value)) {
                $emptyVal = [$x, $y];
            }
        }
    }
    [$x, $y] = $emptyVal;
    $rndVals = getRandValArray();
    $candidates = [];
    foreach ($rndVals as $rndVal) {
        if (canBePlaced($x, $y, $rndVal, $grid)) {
            $candidates[] = $rndVal;
        }
    }
    if ($nextWorker) {
        // parallel
        foreach ($candidates as $rndVal) {
            $tmpGrid = $grid;
            $tmpGrid[$x][$y] = $rndVal;
            $promises[] = $workerPool[$nextWorker]->run(function() use ($tmpGrid, $array) {
                include_once 'genZtsLib.php';
                return solRecur($tmpGrid, $array);
            });
            $nextWorker++; $nextWorker == count($workerPool) -1 ? $nextWorker = 0 : 1;
        }
        // promises holen
        foreach ($promises as $future) {
            $arr = $future->value();
            foreach ($arr as $hash => $resultGrid) {
                if (empty($array[$hash]) && !$resultGrid) {
                    $array[$hash] = $resultGrid;
                }
            }
        }
    } else {
        // normal
        foreach ($candidates as $rndVal) {
            $tmpGrid = $grid;
            $tmpGrid[$x][$y] = $rndVal;
            $tmp = solRecur($tmpGrid, $array);
            unset($tmpGrid);
            if (gettype($tmp) == 'array') {
                foreach ($tmp as $hash => $tmpGrid){
                    if (empty($array[$hash]) && $tmpGrid) {
                        $array[$hash] = $tmpGrid;
                    }
                }
            }
        }
    }
    return $array;
}

function isFilled($grid): bool
{
    foreach ($grid as $x => $row) {
        foreach ($row as $y => $val) {
            if (empty($val)) {
                return false;
            }
        }
    }
    return true;
}

function implode_r($concattinator, $array)
{
    return is_array($array) ?
        implode($concattinator, array_map(__FUNCTION__, array_fill(0, count($array), $concattinator), $array)) :
        $array;
}

function generateSudoku($numPossibleSolutions = 1, $format = 'txt') {
    $grid = [];
    do {
        $grid = generateFull('php');
        $tries = 50;
        do {
            [$x, $y] = [random_int(0, 8), random_int(0, 8)];
            $tmpGrid = $grid;
            $tmpGrid[$x][$y] = null;
            if (count(getPossibleSolutions($tmpGrid, 16)) > $numPossibleSolutions) {
                // don't do it
                $tries--;
                continue;
            } else {
                // do it
                $grid[$x][$y] = null;
            }
            $tries--;
        } while ($tries > 0);
    } while (getPossibleSolutions($grid, 16) < $numPossibleSolutions);

    if (strtolower($format) == 'csv') {
        return toCsv($grid);
    }
    if (strtolower($format) == 'json') {
        return json_encode($grid);
    }
    if (strtolower($format) == 'php') {
        return $grid;
    }
    if (strtolower($format) == 'txt') {
        return makePretty($grid);
    }
    if (strtolower($format) == 'col') {
        return makePrettyColor($grid);
    }
//    if (strtolower($format) == 'png') {
//        return
//    }
    return "unsupported output format";
}

function makePretty(array $grid) {
    foreach ($grid as &$line) {
        foreach ($line as &$val) {
            if (empty($val)) {
                $val = ' ';
            }
        }
    }
    $return = '';
    $return .=  "+---+---+---+\n";
    $return .=  "|".$grid[0][0].$grid[1][0].$grid[2][0]."|".$grid[3][0].$grid[4][0].$grid[5][0]."|".$grid[6][0].$grid[7][0].$grid[8][0]."|\n";
    $return .=  "|".$grid[0][1].$grid[1][1].$grid[2][1]."|".$grid[3][1].$grid[4][1].$grid[5][1]."|".$grid[6][1].$grid[7][1].$grid[8][1]."|\n";
    $return .=  "|".$grid[0][2].$grid[1][2].$grid[2][2]."|".$grid[3][2].$grid[4][2].$grid[5][2]."|".$grid[6][2].$grid[7][2].$grid[8][2]."|\n";
    $return .=  "+---+---+---+\n";

    $return .=  "|".$grid[0][3].$grid[1][3].$grid[2][3]."|".$grid[3][3].$grid[4][3].$grid[5][3]."|".$grid[6][3].$grid[7][3].$grid[8][3]."|\n";
    $return .=  "|".$grid[0][4].$grid[1][4].$grid[2][4]."|".$grid[3][4].$grid[4][4].$grid[5][4]."|".$grid[6][4].$grid[7][4].$grid[8][4]."|\n";
    $return .=  "|".$grid[0][5].$grid[1][5].$grid[2][5]."|".$grid[3][5].$grid[4][5].$grid[5][5]."|".$grid[6][5].$grid[7][5].$grid[8][5]."|\n";

    $return .=  "+---+---+---+\n";
    $return .=  "|".$grid[0][6].$grid[1][6].$grid[2][6]."|".$grid[3][6].$grid[4][6].$grid[5][6]."|".$grid[6][6].$grid[7][6].$grid[8][6]."|\n";
    $return .=  "|".$grid[0][7].$grid[1][7].$grid[2][7]."|".$grid[3][7].$grid[4][7].$grid[5][7]."|".$grid[6][7].$grid[7][7].$grid[8][7]."|\n";
    $return .=  "|".$grid[0][8].$grid[1][8].$grid[2][8]."|".$grid[3][8].$grid[4][8].$grid[5][8]."|".$grid[6][8].$grid[7][8].$grid[8][8]."|\n";
    $return .=  "+---+---+---+\n";

    return $return;
}

function makePrettyColor(array $grid) {
    foreach ($grid as &$line) {
        foreach ($line as &$val) {
            if (empty($val)) {
                $val = ' ';
            }
        }
    }
    $return = '';
    $return .=  "+---+---+---+\n";
    $return .=  "|".$grid[0][0].$grid[1][0].$grid[2][0]."|".$grid[3][0].$grid[4][0].$grid[5][0]."|".$grid[6][0].$grid[7][0].$grid[8][0]."|\n";
    $return .=  "|".$grid[0][1].$grid[1][1].$grid[2][1]."|".$grid[3][1].$grid[4][1].$grid[5][1]."|".$grid[6][1].$grid[7][1].$grid[8][1]."|\n";
    $return .=  "|".$grid[0][2].$grid[1][2].$grid[2][2]."|".$grid[3][2].$grid[4][2].$grid[5][2]."|".$grid[6][2].$grid[7][2].$grid[8][2]."|\n";
    $return .=  "+---+---+---+\n";

    $return .=  "|".$grid[0][3].$grid[1][3].$grid[2][3]."|".$grid[3][3].$grid[4][3].$grid[5][3]."|".$grid[6][3].$grid[7][3].$grid[8][3]."|\n";
    $return .=  "|".$grid[0][4].$grid[1][4].$grid[2][4]."|".$grid[3][4].$grid[4][4].$grid[5][4]."|".$grid[6][4].$grid[7][4].$grid[8][4]."|\n";
    $return .=  "|".$grid[0][5].$grid[1][5].$grid[2][5]."|".$grid[3][5].$grid[4][5].$grid[5][5]."|".$grid[6][5].$grid[7][5].$grid[8][5]."|\n";

    $return .=  "+---+---+---+\n";
    $return .=  "|".$grid[0][6].$grid[1][6].$grid[2][6]."|".$grid[3][6].$grid[4][6].$grid[5][6]."|".$grid[6][6].$grid[7][6].$grid[8][6]."|\n";
    $return .=  "|".$grid[0][7].$grid[1][7].$grid[2][7]."|".$grid[3][7].$grid[4][7].$grid[5][7]."|".$grid[6][7].$grid[7][7].$grid[8][7]."|\n";
    $return .=  "|".$grid[0][8].$grid[1][8].$grid[2][8]."|".$grid[3][8].$grid[4][8].$grid[5][8]."|".$grid[6][8].$grid[7][8].$grid[8][8]."|\n";
    $return .=  "+---+---+---+\n";

    return $return;
}

//@mkdir('out');
//for ($i = 0; $i < 1000; $i++) {
//    $time = microtime(true);
//    file_put_contents('out/'.str_replace(['.', ','], '', $time).'.csv', generate('csv'));
//}
