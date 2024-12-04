<?php

namespace TestBlog\Kernel;

use TestBlog\Kernel\Request;

class RequestParams
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    private $_rawBody;

    public function getRawBody()
    {
        if ($this->_rawBody === null) {
            $this->_rawBody = file_get_contents('php://input');
        }
        return $this->_rawBody;
    }

    private $_queryParams;

    public function getQueryParams()
    {
        if ($this->_queryParams === null) {
            return $this->_queryParams = $_GET;
        }
        return $this->_queryParams;
    }

    private $_bodyParams;

    public function getBodyParams()
    {
        if ($this->_bodyParams === null) {
            $this->_bodyParams = $_POST;
        }
        return $this->_bodyParams;
    }

    private $params = [];
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $params = $this->getRequestParams();
        $this->setParams($params);
    }

    public function getParams()
    {
        return $this->params;
    }

    private function setParams($params = [])
    {
        $this->params = $params;
    }

    public function getParam($name, $defaultValue = null, $allowNull = true)
    {
        if (
            $this->hasParam($name) ||
            ($allowNull && $this->params[$name] === null)
        ) {
            return $this->params[$name];
        }
        return $defaultValue;
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function hasParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            return true;
        }
        return false;
    }

    private function getRequestParams()
    {
        $requestParams = $this->getQueryParams();
        if ($this->request->isJson()) {
            $jsonParams = json_decode($this->getRawBody(), true) ?? [];
            $requestParams = array_merge($requestParams, $jsonParams);
        } else {
            $requestParams = array_merge($requestParams, $this->getBodyParams());
        }
        return $requestParams;
    }

}
