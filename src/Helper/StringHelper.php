<?php

namespace TestBlog\Helper;

class StringHelper
{
    /**
     * @param $string
     * @return string
     */
    public static function toCamelCase($string) {
        // Разделяем строку по дефисам
        $parts = explode('-', $string);

        // Преобразуем первое слово в нижний регистр
        $camelCaseString = strtolower(array_shift($parts));

        // Преобразуем первые буквы остальных слов в верхний регистр
        foreach ($parts as $part) {
            $camelCaseString .= ucfirst(strtolower($part));
        }

        return $camelCaseString;
    }
}
