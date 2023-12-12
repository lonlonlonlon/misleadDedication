<?php

use function Sudoku\getPossibleSolutions;
use function Sudoku\generateSudoku;

include_once("genLib.php");

//$json = "
//[[7,\"\",8,6,\"\",5,3,\"\",4],
//[5,\"\",\"\",4,\"\",\"\",\"\",\"\",\"\"],
//[6,\"\",\"\",\"\",3,7,\"\",5,9],
//[\"\",\"\",\"\",5,\"\",3,7,4,1],
//[4,5,7,2,\"\",1,8,9,3],
//[\"\",\"\",\"\",\"\",7,\"\",2,\"\",5],
//[3,\"\",5,\"\",\"\",\"\",9,\"\",6],
//[9,\"\",\"\",1,\"\",\"\",4,\"\",\"\"],
//[1,\"\",6,3,\"\",9,5,\"\",\"\"]]";
//
////echo(generate('json'));
//const CORE_COUNT = 16;
//$start = microtime(true);
//$sols = getPossibleSolutions(json_decode($json), 16);
//$end = microtime(true);
//echo("Took ".($end-$start)." secs.\n");
//if (!$sols) {
//    echo("Type of sols: ".gettype($sols).PHP_EOL);
//    exit();
//}
//foreach ($sols as $hash => $solution) {
//    echo($hash.":\n");
//    echo (json_encode($solution).PHP_EOL);
//}

echo(generateSudoku(1, 'php-executable'));