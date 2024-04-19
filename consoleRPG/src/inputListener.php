<?php
sleep(1);
system("stty -icanon");
if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_connect($sock, '127.0.0.1', 8001) === false) {
    echo "socket_connect() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error($sock)) . "\n";
}

while ($c = fread(STDIN, 1)) {
    socket_write($sock, $c, strlen($c));
}

socket_close ($sock);
