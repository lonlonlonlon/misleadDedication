<?php

class Spam {
    public function __construct(
        private string $url
    )
    {
    }

    function spam(int $times = 0) {
        /**
         * $times is count of spam. 0 = infinite
         */
        if ($times > 0) {
            for ($i = 0; $i < $times; $i++) {
                $code = "";
                exec("curl --socks5-hostname 127.0.0.1:9051 ".$this->url.' >/dev/null 2>&1', result_code:$code);
                echo $code." ";
            }
        } else {
            while (1) {
                $code = "";
                exec("curl --socks5-hostname 127.0.0.1:9051 ".$this->url.' >/dev/null 2>&1', result_code:$code);
                echo $code." ";
            }
        }
    }
}