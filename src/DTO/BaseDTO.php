<?php

namespace TestBlog\DTO;

use ReflectionClass;
use TestBlog\Kernel\RequestParams;

class BaseDTO implements IBaseDTO
{
    public function __get($name) {
        $getterMethodName = 'get' . ucfirst($name);
        if (method_exists($this, $getterMethodName)) {
            return $this->$getterMethodName();
        }
        throw new \RuntimeException("Property '$name' does not exist.");
    }

    public function __set($name, $value) {
        $setterMethodName = 'set' . ucfirst($name);
        if (method_exists($this, $setterMethodName)) {
            $this->$setterMethodName($value);
        } else {
            throw new \RuntimeException("Setter method for property '$name' does not exist.");
        }
    }

    public function loadParams($params = [])
    {
        $reflection = new ReflectionClass(static::class);
        foreach ($params as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);
                if ($property->isPublic()) {
                    $this->__set($key, $value);
                }
            }
        }
    }

    public function toArray()
    {
        $objectVars = get_object_vars($this);
        $result = [];
        foreach ($objectVars as $key => $value) {
            $result[$key] = $value;
        }
        return $result;
    }

    public static function fromArray(array $array): IBaseDTO
    {
        $thisInstance = new static();
        $thisInstance->loadParams($array);
        return $thisInstance;
    }
}
