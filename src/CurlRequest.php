<?php
namespace BaseCode\CurlRequest;

Class CurlRequest {

    private $curl;
    private $cookies = [];
    private $options = [];
    private $info;
    private $response;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->options = [];
        $this->ssl(false);
        $this->method('GET');
        $this->custom(CURLOPT_RETURNTRANSFER, true);
    }

    public function url(string $url): CurlRequest
    {
        $this->custom(CURLOPT_URL, $url);
        return $this;
    }

    public function method(string $method): CurlRequest
    {
        $this->custom(CURLOPT_CUSTOMREQUEST, $method);
        return $this;
    }

    public function headers(array $headers): CurlRequest
    {
        $this->custom(CURLOPT_HTTPHEADER, $headers);
        return $this;
    }

    public function data($data): CurlRequest
    {
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        $this->custom(CURLOPT_POST, true);
        $this->custom(CURLOPT_POSTFIELDS, $data);
        return $this;
    }

    public function cookies(string $name): CurlRequest
    {
        if (!isset($this->cookies[$name])) {
            if (!defined('DS')) {
                define('DS', DIRECTORY_SEPARATOR);
            }

            $file = dirname(__DIR__).DS.'cookies'.DS.md5(uniqid('cookie_')).'.txt';
            $this->cookies[$name] = $file;
        }
        
        $this->custom(CURLOPT_COOKIEJAR, $this->cookies[$name]);
        $this->custom(CURLOPT_COOKIEFILE, $this->cookies[$name]);
        return $this;
    }

    public function custom(int $indexe, $value): CurlRequest
    {
        $this->options[$indexe] = $value;
        return $this;
    }

    public function ssl(bool $bool): CurlRequest
    {
        $this->custom(CURLOPT_SSL_VERIFYPEER, $bool);
        $this->custom(CURLOPT_SSL_VERIFYHOST, $bool);
        return $this;
    }

    public function execute($close = true): CurlRequest
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, $this->options);
        $this->response = curl_exec($this->curl);
        
        if (!curl_errno($this->curl)) {
            $this->info = curl_getinfo($this->curl);
        }

        if ($close) {
            $this->close();
        }

        $this->reset();
        return $this;
    }

    public function info(string $name)
    {
        if (isset($this->info[$name])) {
            return $this->info[$name];
        }
        return null;
    }

    public function response()
    {
        if ($this->response) {
            return $this->response;
        }
        return null;
    }

    private function close()
    {
        if ($this->curl) {
            curl_close($this->curl);
        }
    }

    public function __destruct()
    {
        if ($this->cookies) {
            foreach ($this->cookies as $cookie) {
                if (file_exists($cookie)) {
                    unlink($cookie);
                }
            }
        }
    }
}