<?php

namespace TestBlog\DTO;

class LoginDTO extends BaseDTO
{
    public $username;
    public $password;

    public function setUsername($value)
    {
        $value = trim($value);
        if (empty($value)) {
            throw new \InvalidArgumentException("Имя пользователя не может быть пустым");
        }
        $this->username = $value;
    }

    public function setPassword($value)
    {
        $value = trim($value);
        if (empty($value)) {
            throw new \InvalidArgumentException("Пароль не может быть пустым");
        }
        $this->password = $value;
    }
}
