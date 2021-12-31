<?php
define('DS', DIRECTORY_SEPARATOR);

require dirname(__DIR__).DS.'vendor'.DS.'autoload.php';

use BaseCode\CurlRequest\CurlRequest;

$curl = new CurlRequest();

$url = 'https://localhost/basecode/curlrequest/examples/method.php';

$curl->url($url)->execute(true);
// echo $curl->info('http_code');
echo $curl->response();
