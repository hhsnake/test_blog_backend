<?php

namespace TestBlog\Kernel;

class Session
{

    public static function start($sessionId)
    {
        session_id($sessionId);
        session_start();
    }

    public static function destroy()
    {
        self::unsetCookie();
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public static function unsetCookie()
    {
        $key = session_name();
        unset($_COOKIE[$key]);
        setcookie($key, '', time() - 3600, '/');
    }

    public static function isAuth()
    {
        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['auth'])) {
            return true;
        }
        return false;
    }

    public static function getAuth()
    {
        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['auth'])) {
            return $_SESSION['auth'];
        }
        return null;
    }
    
    public static function create($vars)
    {
        self::destroy();
        session_start([
            'cookie_lifetime' => 86400,
        ]);
        $sessionId = session_id();
        foreach ($vars as $key => $value) {
            $_SESSION[$key] = $value;
        }
        return $sessionId;
    }

    public static function close()
    {
        session_write_close();
    }
    
}
