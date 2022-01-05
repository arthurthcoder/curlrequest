# **CurlRequest**

![route license](https://img.shields.io/github/license/arthurthcoder/curlrequest?color=%2332C754&logo=MIT)
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/arthurthcoder/curlrequest)

## Getting Started

### Installation

You can install the ( curlrequest ) in your project with composer.

Just run the command below on your terminal:

```bash
composer require basecode/curlrequest
```
or in your composer.json require:

```bash
"basecode/curlrequest": "1.0.*"
```

## Usage

Simple usage example:

```php
use BaseCode\CurlRequest\CurlRequest;

$curl = new CurlRequest();

$curl->standards(function() use ($curl) {
    $curl->custom(CURLOPT_MAXREDIRS, 10);
});

$curl->url('https://www.google.com'); // define the request url

$curl->headers([
    'referer: https://localhost.com'
]); // set headers to send

$curl->execute(true); // execute the request ( pass true to close the connection the default is false )
// The connection is also closed automatically at the end of use.

echo $curl->response(); // get response data

/* shortened request
    echo $curl->url('https://www.google.com')->execute()->response();
*/

if ($curl->error()) {
    echo $curl->error(); // returns last error message
}

/*
    OPTIONS OF PARAMS FOR RETURN RESPONSE METHOD

    $curl->response() = $curl->response('content');
    $curl->response('content'); \\ default return
    $curl->response('url');
    $curl->response('content_type');
    $curl->response('http_code');
    $curl->response('header_size');
    $curl->response('request_size');
    $curl->response('filetime');
    $curl->response('ssl_verify_result');
    $curl->response('redirect_count');
    $curl->response('total_time');
    $curl->response('namelookup_time');
    $curl->response('connect_time');
    $curl->response('pretransfer_time');
    $curl->response('size_upload');
    $curl->response('size_download');
    $curl->response('speed_download');
    $curl->response('speed_upload');
    $curl->response('download_content_length');
    $curl->response('upload_content_length');
    $curl->response('starttransfer_time');
    $curl->response('redirect_time');
    $curl->response('certinfo');
    $curl->response('request_header');
*/
```