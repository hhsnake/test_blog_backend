<?php

namespace TestBlog\DTO;

use TestBlog\DTO\BaseDTO;

class RegisterDTO extends LoginDTO
{
    public $email;

    public function setEmail($value) {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->email = strtolower($value);
        } else {
            throw new \InvalidArgumentException("Invalid email address provided.");
        }
    }
}
