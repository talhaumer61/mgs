<?php

/**
 *
 * Session Class - Handle the sessions
 *
 * @see https://www.php.net/manual/en/book.session.php
 */

class Auth
{

  private static $key = 'userlogininfo';
  private static $username = 'LOGINNAME';

  /**
   * @param $user
   *
   * LOGIN the user to the current application. by setting the user session.
   */
  public static function login($user)
  {
      Session::set(self::$key, $user);
      $_SESSION[self::$key]['LOGINIDA'] = $user->user_id;
  }

  public static function username()
  {
    return Session::get(self::$key)[self::$username];
  }

  /**
   * @return ArrayObject|false
   *
   * Check if the user is logged in or not
   */
  public static function isLogin()
  {
      return Session::get(self::$key);
  }

  /**
   * @return object |false
   *
   * Return the User array from the session.
   */
  public static function user()
  {
    return (object)Session::get(self::$key);
  }

  public static function user_id()
  {
    return $_SESSION['userlogininfo']['LOGINIDA'];
  }

  /**
   * @return bool
   *
   * Logout the current user from the application and destroy the sessions
   */
  public static function logout(){
      Session::unset(self::$key);
      Session::destroy();
      return true;
  }

  public static function isAdmin(){
    if(self::isLogin()){
      $user = Session::get(self::$key);
      if ($user->is_admin)
          return true;
      else
          return false;
    }
  }
}