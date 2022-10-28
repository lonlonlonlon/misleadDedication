<?php

namespace Sudoku;

require_once 'src/Grid.php';

use Sudoku\Grid;

$grid = new Grid();

$grid->setVal(0,0,1);
$grid->setVal(8,8,9);
/**
 * 0 0 0 | 0 0 0 | 0 0 0
 * 0 0 0 | 0 0 0 | 0 0 0
 * 0 0 0 | 0 0 0 | 0 0 0
 * _____________________
 * 0 0 0 | 0 0 0 | 0 0 0
 * 0 0 0 | 0 0 0 | 0 0 0
 * 0 0 0 | 0 0 0 | 0 0 0
 * _____________________
 * 0 0 0 | 0 0 0 | 0 0 0
 * 0 0 0 | 0 0 0 | 0 0 0
 * 0 0 0 | 0 0 0 | 0 0 0
 */

echo($grid->toCsv().PHP_EOL);