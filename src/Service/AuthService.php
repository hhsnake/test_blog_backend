<?php

namespace TestBlog\Service;

use TestBlog\Kernel\App;
use TestBlog\DTO\IBaseDTO;
use TestBlog\Entity\User;
use TestBlog\Exception\HttpException;
use TestBlog\Kernel\Session;

class AuthService
{

    public function loginUser(IBaseDTO $loginDTO)
    {
        $em = App::getInstance()->db->entityManager;
        $userRepository = $em->getRepository(User::class);
        /** @var User $userModel */
        $userModel = $userRepository->findOneBy(['name' => $loginDTO->username]);
        if (!$userModel) {
            throw new HttpException('Неправильное имя пользователя или пароль', 401);
        }
        $crypt = new CryptService();
        $key = $crypt->getKey($userModel->getName(), $userModel->getSalt());
        if ($userModel->getHash() !== $crypt->encrypt($loginDTO->password, $key)) {
            throw new HttpException('Неправильное имя пользователя или пароль', 401);
        }
        $sessionId = Session::create(['auth' => $userModel]);
        Session::close();
        return $sessionId;
    }

    public function registerUser(IBaseDTO $registerDTO)
    {
        $em = App::getInstance()->db->entityManager;
//        $userRepository = $em->getRepository(User::class);
//        $expressionBuilder = Criteria::expr();
//        $expr = $expressionBuilder->orX(
//            $expressionBuilder->eq('name', $registerDTO->username),
//            $expressionBuilder->eq('email', $registerDTO->email)
//        );
//        /** @var User[] $userModels */
//        $userModels = $userRepository->matching(new Criteria($expr));
//        if (count($userModels)) {
//            throw new HttpException('Пользователь уже существует', 400);
//        }

        $crypt = new CryptService();
        $salt = $crypt->getSalt();
        $key = $crypt->getKey($registerDTO->username, $salt);
        $userModel = (new User())
            ->setName($registerDTO->username)
            ->setEmail($registerDTO->email)
            ->setHash($crypt->encrypt($registerDTO->password, $key))
            ->setSalt($salt);
        $em->persist($userModel);
        $em->flush();
        return $userModel;
    }

    public function restoreUser($email)
    {
        $em = App::getInstance()->db->entityManager;
        $userRepository = $em->getRepository(User::class);
        /** @var User $userModel */
        $userModel = $userRepository->findOneBy(['email' => $email]);
        if (!$userModel) {
            throw new HttpException('Пользователь не существует', 400);
        }
        $crypt = new CryptService();
        $key = $crypt->getKey($userModel->getName(), $userModel->getSalt());
        return $crypt->decrypt($userModel->getHash(), $key);
    }

}
