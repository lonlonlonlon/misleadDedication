<?php

class FuckShit
{

    public function start()
    {
        $a=0;
        while (1) {
            echo($a."\n");
            $a+=1;
        }
    }
}

$fuckShit = new FuckShit();
$fuckShit->start();