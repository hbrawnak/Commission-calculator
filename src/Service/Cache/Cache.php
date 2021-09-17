<?php


namespace App\CommissionTask\Service\Cache;


class Cache
{
    const SESSION_STARTED     = true;
    const SESSION_NOT_STARTED = false;

    private $sessionState = self::SESSION_NOT_STARTED;
    private static $instance;


    private function __construct()
    {
    }


    /**
     * @return Cache
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        self::$instance->startSession();

        return self::$instance;
    }

    /**
     * @return bool
     */
    public function startSession()
    {
        if ($this->sessionState == self::SESSION_NOT_STARTED) {
            $this->sessionState = session_start();
        }

        return $this->sessionState;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION['user'][$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function get($key)
    {
        return isset($_SESSION['user'][$key]) ? $_SESSION['user'][$key] : '';
    }

    /**
     * @param $key
     */
    public static function delete($key)
    {
        unset($_SESSION['user'][$key]);
    }

    /**
     * @return bool
     */
    public function destroy()
    {
        if ($this->sessionState == self::SESSION_STARTED) {
            $this->sessionState = !session_destroy();
            unset($_SESSION);

            return !$this->sessionState;
        }

        return false;
    }
}