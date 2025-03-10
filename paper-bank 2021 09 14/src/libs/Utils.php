<?php

/**
 * Class Utils
 */
class Utils
{
    /**
     * Redirect the User with the Flash Message
     * @param {string} $route - The route where you want to redirect the user
     */
    protected static function redirectWithFlash($route, $flash = null)
    {
        self::setFlash($flash);
        self::redirectWithOutFlash($route);
    }

    /**
     * Redirect the User without the Flash Message
     * @param {string} $route - The route where you want to redirect the user
     *
     * @return \http\Header;
     */
    protected static function redirectWithOutFlash($route)
    {
        return header("Location:" . URLROOT . "/$route", true, 302);
    }

    /**
     * Redirect the User to any route
     * @param {string} $route - The route where you want to redirect the user
     *
     * @return \http\Header;
     */
    public static function redirect($route, $flash = null)
    {
        if ($flash){
            return self::redirectWithFlash($route, $flash);
        }else {
            return self::redirectWithOutFlash($route);
        }
    }

    /**
     * Include the file form inside the /src directory
     * @param {string} $filePath - The route to the file you want to import
     */
    public static function include($filePath)
    {
        return require_once(APPROOT . "/$filePath");
    }

    /**
     * Set Flash Message on the Sessions
     * @param $flash {Array} - Flash message you want to Store
     */
    public static function setFlash($flash)
    {
        Session::set('flash', $flash);
    }

    /**
     * Get Flash Message from the Sessions
     *
     * @return array
     */
    public static function getFlash()
    {
        $flash = Session::get('flash');
        self::unsetFlash();
        return $flash;
    }

    /**
     * Unset the Flash Message from the Sessions
     *
     */
    public static function unsetFlash()
    {
         unset($_SESSION['flash']);
    }
}
