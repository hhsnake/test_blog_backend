<?php

namespace TestBlog\Exception;

class HttpException extends \Exception
{
    /**
     * @return mixed|string
     */
    public function getError()
    {
        $codes = $this->getData('codes');
        return array_key_exists($this->code, $codes) ? $codes[$this->code] : 'Unknown error';
    }

    /**
     * @param string $type
     * @return array
     */
    private function getData(string $type): array
    {
        return require(__DIR__ . "/$type.php");
    }
}
