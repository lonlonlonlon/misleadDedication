<?php
$ip_address = '8.8.8.8'; // IP address you'd like to ping.
$count = 10;
$pingDangerLimit = 100;
while (1) {
    $add = 0;
    $danger = 0;
    for ($i = 0; $i < $count; $i++) {
        sleep(1);
        exec("ping -c 1 " . $ip_address . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
        $time = preg_split('/=/', $ping_time[0])[1];
        $add += $time;
        if ($time > $pingDangerLimit) {
            $danger += 1;
        }
    }
    $avg = $add / $count;
    $dangerPercent = $danger / $count * 100;
    $time = new DateTime('now');
    $time = $time->format("Y-m-d H:i:s");
    file_put_contents("pingTime.log", "$time\tping avg.:\t$avg\tpings higher than dangerlimit $pingDangerLimit : $dangerPercent%\n", 8);
    unset($ping_time);
}
