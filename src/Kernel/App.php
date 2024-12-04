<?php

namespace TestBlog\Kernel;

use TestBlog\Exception\HttpException;
use TestBlog\Helper\StringHelper;

class App
{
    private const BASE_NAMESPACE_CONTROLLER = 'TestBlog\Controller';

    private static ?App $instance = null;
    public Db $db;
    public Request $request;
    public Response $response;
    public $auth;

    private function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    public function setDb(Db $db): self
    {
        $this->db = $db;
        return $this;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run(): void
    {
        $path = $this->request->parseRequest();
        list (, $basePath, $controller, $method) = explode('/', $path);
        $result = null;
        try {
            $result = $this->runMethod($basePath, $controller, $method);
        } catch (HttpException $e) {
            $result = $this->response->formatError($e);
        } catch (\Exception $e) {
            $result = $this->response->formatError(new \Exception($e->getMessage(), 400));
        }
        echo $this->response->formatResponse($result);
    }

    protected function runMethod($basePath, $controller, $method)
    {
        $runController = ucfirst(StringHelper::toCamelCase($controller));
        $runMethod = StringHelper::toCamelCase($method);
        $parts = explode('\\', self::BASE_NAMESPACE_CONTROLLER);
        $parts[] = $basePath;
        $parts[] = $runController . 'Controller';
        $controllerNamespace = implode('\\', $parts);
        if (!class_exists($controllerNamespace)) {
            throw new HttpException('Заданный путь не существует', 404);
        }
        try {
            // Создаем объект рефлексии для класса
            $reflectionClass = new \ReflectionClass($controllerNamespace);

            // Проверяем, существует ли метод в классе
            $reflectionClass->hasMethod($runMethod);
        } catch (\ReflectionException $e) {
            throw new HttpException('Заданный путь не существует', 404);
            
        }
        $controllerObject = new $controllerNamespace();
        $controllerObject->beforeAction($runMethod);
        return call_user_func_array([$controllerObject, $runMethod], [$this->request->requestParams]);
    }
}
