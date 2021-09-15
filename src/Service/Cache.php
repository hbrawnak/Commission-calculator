<?php


namespace Paysera\CommissionTask\Service;


class Cache
{

    public static function set($key, $value)
    {
        $_SESSION['user'][$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION['user'][$key]) ? $_SESSION['user'][$key] : '';
    }

    public function delete($key)
    {
        unset($_SESSION['user'][$key]);
    }

}