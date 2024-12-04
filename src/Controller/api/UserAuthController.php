<?php

namespace TestBlog\Controller\api;

use TestBlog\DTO\RegisterDTO;
use TestBlog\Exception\HttpException;
use TestBlog\Kernel\Request;
use TestBlog\Kernel\RequestParams;
use TestBlog\Service\AuthService;

class UserAuthController extends ApiController
{
    public $requireAuth = false;

    /**
     * @return array[]
     */
    public function getOptions(): array
    {
        return [
            'getToken' => [Request::METHOD_POST, Request::METHOD_OPTIONS],
            'register' => [Request::METHOD_POST, Request::METHOD_OPTIONS],
            'restore' => [Request::METHOD_POST, Request::METHOD_OPTIONS],
        ];
    }

    /**
     * @param RequestParams $requestParams
     * @return array
     * @throws HttpException
     */
    public function getToken(RequestParams $requestParams)
    {
        $loginDTO = RegisterDTO::fromArray($requestParams->getParams());
        $loginDTO->loadParams($requestParams->getParams());
        $authService = new AuthService();
        $token = $authService->loginUser($loginDTO);

        return [
            'message' => 'Успешная аутентификация',
            'data' => [
                'token' => $token,
            ]
        ];
    }

    /**
     * @param RequestParams $requestParams
     * @return string[]
     * @throws HttpException
     */
    public function register(RequestParams $requestParams)
    {
        $registerDTO = RegisterDTO::fromArray($requestParams->getParams());
        $authService = new AuthService();
        $userModel = $authService->registerUser($registerDTO);

        if (!$userModel || $userModel->getId() === null) {
            throw new HttpException('Ошибка регистрации нового пользователя', 500);
        }

        return [
            'message' => 'Успешная регистрация нового пользователя'
        ];
    }

    /**
     * @param RequestParams $requestParams
     * @return array
     * @throws HttpException
     */
    public function restore(RequestParams $requestParams)
    {
        $email = $requestParams->getParam('email');
        $authService = new AuthService();
        $password = $authService->restoreUser($email);

        return [
            'message' => 'Успешно восстановлен',
            'data' => ['password' => $password],
        ];
    }
}
