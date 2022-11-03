<?php

use parallel\Runtime;

$start = microtime(true);

if (!empty($argv)) {
    $filename = $argv[0];
    $isMain = $argv[1];
}

$threads = [];
for ($i=0;$i<2;$i++){
    $runtime = new Runtime;
    $future = $runtime->run(function () use ($filename, $isMain) {
        echo(getcwd().PHP_EOL);
        echo(getcwd()."/".$filename.PHP_EOL);
        !empty($isMain) ? include_once getcwd()."/".$filename : $a='a';
    }, [$i]);
    $threads[] = $future;
}

foreach ($threads as $future){
    $future->value();
}
$end = microtime(true);
echo "done\ntook ".(($end - $start))." seconds.\n";
?>