<?php

namespace spartaksun\addresses\components;


class Session
{

    /*
     * Key used for store message data
     */
    const FLASH_KEY = 'message';

    /**
     * @var Session instance of this class
     */
    private static $instance;


    private function __construct()
    {
        if (session_id() == '') {
            session_start();
        }
    }

    /**
     * Singleton instance of this class
     * @return Session
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Set value to session
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Delete key from session
     * @param $key
     */
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Receive stored value for key
     * @param $key
     * @return bool
     */
    public function get($key)
    {
        if (isset($_SESSION) && isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return false;
    }

    /**
     * Write message for user into session
     * @param $message
     */
    public function setFlash($message)
    {
        $_SESSION[self::FLASH_KEY] = $message;
    }

    /**
     * Check if isset session message for user
     * @return bool
     */
    public function hasFlash()
    {
        return isset($_SESSION[self::FLASH_KEY]);
    }

    /**
     * Read message from user session and delete it
     * @return bool|string
     */
    public function getFlash()
    {
        if(isset($_SESSION[self::FLASH_KEY])) {
            $message = $_SESSION[self::FLASH_KEY];
            unset($_SESSION[self::FLASH_KEY]);

            return $message;
        }

        return false;
    }

    /**
     * Avoid cloning
     */
    private function __clone()
    {
    }
}