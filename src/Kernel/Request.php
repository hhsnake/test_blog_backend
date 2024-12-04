<?php

namespace TestBlog\Kernel;

class Request
{
    private ?array $headers = null;
    public RequestParams $requestParams;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_OPTIONS = 'OPTIONS';

    public function __construct()
    {
        $this->requestParams = new RequestParams($this);
    }

    public function parseRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $parsedUrl = parse_url($requestUri);
        $path = array_key_exists('path', $parsedUrl) ? $parsedUrl['path'] : '/';
        return $path;
    }

    protected function getHeaders()
    {
        if ($this->headers === null) {
            $headers = getallheaders();
            foreach ($headers as $key => $header) {
                $this->headers[$key] = $header;
            }
        }
        return $this->headers;
    }

    protected function getHeader($name)
    {
        $headers = $this->getHeaders();
        if (array_key_exists($name, $headers)) {
            return $headers[$name];
        }
        return null;
    }
    
    public function hasHeader($name)
    {
        $headers = $this->getHeaders();
        if (array_key_exists($name, $headers)) {
            return true;
        }
        return false;
    }

    public function isJson()
    {
        if ($this->hasHeader(Header::CONTENT_TYPE) && $this->getHeader(Header::CONTENT_TYPE) === Header::CONTENT_TYPE_JSON) {
            return true;
        }
        return false;
    }

    public function getToken()
    {
        $authHeader = $this->getHeader(Header::AUTHORIZATION);
        $pattern = '/^Token\s+(.*?)$/';
        if (preg_match($pattern, $authHeader, $matches)) {
            return $matches[1];
        } else {
            return null;
        }

    }

    public function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }
        return self::METHOD_GET;
    }

    public function isGet(): bool
    {
        return $this->getMethod() === self::METHOD_GET;
    }

    public function isPost(): bool
    {
        return $this->getMethod() === self::METHOD_POST;
    }

    public function isOptions(): bool
    {
        return $this->getMethod() === self::METHOD_OPTIONS;
    }
}
