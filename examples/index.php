<?php
define('DS', DIRECTORY_SEPARATOR);

require dirname(__DIR__).DS.'vendor'.DS.'autoload.php';

use BaseCode\CurlRequest\CurlRequest;

$curl = new CurlRequest();

$url = 'https://localhost/basecode/curlrequest/examples/test.php';

$curl->standards(function() use ($curl) {
    $curl->custom(CURLOPT_MAXREDIRS, 10);
});

echo $curl->url($url)->execute(true)->response();

if ($curl->error()) {
    echo $curl->error();
}