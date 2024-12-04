<?php

namespace TestBlog\Controller\api;

use TestBlog\Kernel\App;
use TestBlog\Kernel\Request;
use TestBlog\Exception\HttpException;
use TestBlog\Kernel\Header;
use TestBlog\Kernel\Session;

abstract class ApiController
{
    // Нужна аутентификация при доступе к контроллеру
    public $requireAuth = true;

    public function beforeAction($method)
    {
        $requestInstance = App::getInstance()->request;
        // CORS
        App::getInstance()->response->setHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Max-Age' => 86400,
            'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, Accept, Origin, Authorization'
        ]);
        // OPTIONS
        if ($requestInstance->isOptions()) {
            $actionOptions = $this->getOptions();
            App::getInstance()->response->setHeader(
            'Access-Control-Allow-Methods',
            array_key_exists($method, $actionOptions) ? implode(',', $actionOptions[$method]) : Request::METHOD_OPTIONS,
            );
            throw new HttpException('', 204);
        }
        if ($requestInstance->isPost() && !$requestInstance->isJson()) {
            throw new HttpException('Тип запроса должен быть ' . Header::CONTENT_TYPE_JSON, 400);
        }
        if ($this->requireAuth) {
            $token = App::getInstance()->request->getToken();
            Session::start($token);
            if (!Session::isAuth()) {
                Session::destroy();
                throw new HttpException('Необходима аутентификация', 401);
            }
        }
    }

    abstract function getOptions();

}
