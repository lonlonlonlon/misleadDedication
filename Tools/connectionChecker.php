<?php

require_once 'vendor/autoload.php';

$client = (new \Symfony\Component\HttpClient\HttpClient())->create();

while (1) {
    $http = $client->request('GET', "http://".$argv[1], ['timeout' => 2.5]);
    $https = $client->request('GET', "https://".$argv[1], ['timeout' => 2.5]);
    try {
        $httpStatusCode = $http->getStatusCode();
    } catch (\Throwable $th) {
        $httpStatusCode = $th->getCode() . " " . $th->getMessage();
    }
    try {
        $httpsStatusCode = $https->getStatusCode();
    } catch (\Throwable $th) {
        $httpsStatusCode = $th->getCode() . " " . $th->getMessage();
    }
    echo(microtime(true).": http: $httpStatusCode\n");
    echo(microtime(true).": https: $httpsStatusCode\n");
    sleep(2);
}