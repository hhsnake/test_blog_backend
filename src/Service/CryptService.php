<?php

namespace TestBlog\Service;

class CryptService
{

    public function encrypt($password, $key)
    {
        $encryption_key = hash('sha256', $key, true);
        $iv = substr(md5($key), -openssl_cipher_iv_length('aes-256-cbc'));
        $output = openssl_encrypt($password, 'aes-256-cbc', $encryption_key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($output);
    }

    public function decrypt($string, $key)
    {
        $encryption_key = hash('sha256', $key, true);
        $encryptedData = base64_decode($string);
        $iv = substr(md5($key), -openssl_cipher_iv_length('aes-256-cbc'));
        $output = openssl_decrypt($encryptedData, 'aes-256-cbc', $encryption_key, OPENSSL_RAW_DATA, $iv);
        return $output;
    }

    public function getSalt()
    {
        return md5(microtime(true));
    }

    public function getKey($username, $salt): string
    {
        return md5($username . '|' . $salt);
    }

}
