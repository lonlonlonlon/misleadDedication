<?php

use parallel\Runtime;

$start = microtime(true);

$threads = [];
for ($i=0;$i<100;$i++){
    $runtime = new Runtime;
    $future = $runtime->run(function ($i) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com?q=$i");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result =  curl_exec($ch);
        echo $i.PHP_EOL;
        return $result;
    }, [$i]);
    $threads[] = $future;
}

foreach ($threads as $future){
    $future->value();
}
$end = microtime(true);
echo "done\ntook ".(($end - $start))." seconds.\n";
?>