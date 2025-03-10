<?php

/**
 *
 * Session Class - Handle the sessions
 *
 * @see https://www.php.net/manual/en/book.session.php
 */

class Session
{
    private static $session = null;

    /**
     * Start the session by invoking session_start()
     */
    public static function start()
    {
        if (!isset($_SESSION)) {
            session_start();
            self::$session = &$_SESSION;
        }
    }

    /**
     * @return array | bool - Returns the whole session array
     */
    public static function all()
    {
        if (isset($_SESSION)) {
            return $_SESSION;
        }else{
            return false;
        }
    }


    /**
     * Destroy the session
     */
    public static function destroy()
    {
        if (self::$session) {
            session_destroy();
            self::$session = null;
        }
    }

    /**
     * @param $key {string} - The key for the session array
     * @param $value {any} - Value of the session key
     */
    public static function set($key, $value)
    {
        self::$session[$key] = $value;
    }

    /**
     * @param $key - The key to retreve the value from array
     *
     * @return ArrayObject | false
     */
    public static function get($key)
    {
        if (isset(self::$session[$key])){
            return self::$session[$key];
        }else {
            return false;
        }
    }

    /**
     * @param $key - The key to unset the value from array
     *
     * @return ArrayObject | false
     */
    public static function unset($key)
    {
        if (isset(self::$session[$key])){
            unset(self::$session[$key]);
            return true;
        }else {
            return false;
        }
    }
}