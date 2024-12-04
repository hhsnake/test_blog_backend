<?php

namespace TestBlog\Kernel;

use TestBlog\Exception\HttpException;

class Response
{

    private $header = [];

    public function formatResponse($response)
    {
        if (empty($response)) {
            $e = new HttpException('Что-то пошло не так...', 500);
            $response = $this->formatError($e);
        }
        $code = 200;
        if (is_array($response) && array_key_exists('code', $response)) {
            $code = $response['code'];
            unset($response['code']);
        }
        http_response_code($code);
        $this->setHeader(Header::CONTENT_TYPE, Header::CONTENT_TYPE_JSON_CHARSET);
        $this->echoHeader();
        return json_encode($response);
    }

    protected function echoHeader()
    {
        foreach ($this->header as $key => $header) {
            header($key . ': ' . $header);
        }
    }

    public function redirect($urlRedirectTo, $responseCode = 302)
    {
        header('Location: ' . $urlRedirectTo, true, $responseCode);
        exit;
    }

    public function formatError(\Exception $e)
    {
        if ($e->getCode() >= 200 and $e->getCode() < 300) {
            return [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
        if ($e instanceof HttpException) {
            return [
                'error' => $e->getError(),
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
        return [
            'error' => 'Error',
            'message' => $e->getMessage(),
            'code' => $e->getCode() === 0 ? 400 : $e->getCode()
        ];
    }

    public function setHeaders($header = [])
    {
        $this->header = $header;
    }

    public function setHeader(string $name, string $value)
    {
        $this->header[$name] = $value;
    }

    public function removeHeader(string $name)
    {
        unset($this->header[$name]);
    }

}
