<?php

namespace TestBlog\Controller\api;

use TestBlog\Kernel\Request;
use TestBlog\Kernel\Session;

class UserMgmtController extends ApiController
{

    public $requireAuth = true;

    /**
     * @return array[]
     */
    public function getOptions(): array
    {
        return [
            'logout' => [Request::METHOD_POST, Request::METHOD_OPTIONS],
        ];
    }

    /**
     * @return string[]
     */
    public function logout()
    {
        Session::destroy();
        return ['message' => 'Успешный выход из системы'];
    }

}