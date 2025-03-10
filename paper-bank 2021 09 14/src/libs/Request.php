<?php

class Request {
    /**
     * Return the Request method
     * @return mixed
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Return the Request post Data
     * @param $key - Associated key value
     *
     * @return array
     */
    public static function body($key = null)
    {
        if (isset($key))
            if(isset($_POST[$key]) && !empty($_POST[$key]))
                return $_POST[$key];
            else
                return false;

        return $_POST;
    }

    /**
     * Return the Request get Data
     * @return array
     */
    public static function query($key = null)
    {
        if (isset($key))
            if(isset($_GET[$key]) && !empty($_GET[$key]))
                return $_GET[$key];
            else
                return false;

        return $_GET;
    }
}