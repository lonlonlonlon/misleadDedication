<?php
sleep(1);
system("stty -icanon");
if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_connect($sock, '127.0.0.1', 8001) === false) {
    echo "socket_connect() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error($sock)) . "\n";
}
$_ENV['inputListenerSock'] = $sock;
register_shutdown_function(function () {
    socket_shutdown($_ENV['inputListenerSock']);
    exit(0);
});

while ($c = fread(STDIN, 1)) {
    socket_write($sock, $c, strlen($c));
    system('clear');
}

socket_close ($sock);
