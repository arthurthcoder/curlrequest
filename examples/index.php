<?php
define('DS', DIRECTORY_SEPARATOR);

require dirname(__DIR__).DS.'vendor'.DS.'autoload.php';

use BaseCode\CurlRequest\CurlRequest;

$curl = new CurlRequest();

$url = 'https://localhost/basecode/curlrequest/examples/test.php';


echo $curl->url($url)->method('POST')->data([
    'email' => 'contato@thcoder.com',
])->execute(true)->response();

if ($curl->error()) {
    echo $curl->error();
}