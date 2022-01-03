<?php
namespace BaseCode\CurlRequest;

Class CurlRequest {

    private $curl;
    private $standards;
    private $cookies = [];
    private $options = [];
    private $response = [];
    private $error;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->options = [];

        $this->method('GET');

        $this->custom(CURLOPT_SSL_VERIFYPEER, false);
        $this->custom(CURLOPT_SSL_VERIFYHOST, false);
        $this->custom(CURLOPT_RETURNTRANSFER, true);
    }

    public function standards(callable $standards): CurlRequest
    {
        $this->standards = $standards;
        return $this;
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

    public function execute($close = true): CurlRequest
    {
        $this->curl = curl_init();

        if (is_callable($this->standards)) {
            call_user_func($this->standards);
        }

        curl_setopt_array($this->curl, $this->options);

        $this->response = [];
        $this->response['content'] = curl_exec($this->curl);
        
        if (!curl_errno($this->curl)) {
            $this->response = array_merge($this->response, curl_getinfo($this->curl));
        }else{
            $this->error = curl_error($this->curl);
        }

        if ($close) {
            $this->close();
        }

        $this->reset();
        return $this;
    }

    public function response(string $name = null)
    {
        if ($name) {
            return isset($this->response[$name]) ? $this->response[$name] : null;
        }
        
        return isset($this->response['content']) ? $this->response['content'] : null;
    }

    private function close()
    {
        if ($this->curl) {
            curl_close($this->curl);
        }
    }

    public function error(): ?string
    {
        if ($this->error) {
            return $this->error;
        }

        return null;
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