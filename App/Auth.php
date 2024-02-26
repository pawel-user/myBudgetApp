<?php

namespace App;

use App\Models\User;
use App\Models\RememberedLogin;

/**
 * Authentication
 *
 * PHP version 7.4
 */
class Auth
{
    /**
     * Login the user
     *
     * @param User $user The user model
     * @param boolean $remember_me Remember the login if true
     *
     * @return void
     */
    public static function login($user, $remember_me)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;

        if ($remember_me) {

            if ($user->rememberLogin()) {

                setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');

            }
        }
    }

    /**
     * Logout the user
     *
     * @return void
     */
    public static function logout()
    {
      // Unset all of the session variables
      $_SESSION = [];

      // Delete the session cookie
      if (ini_get('session.use_cookies')) {
          $params = session_get_cookie_params();

          setcookie(
              session_name(),
              '',
              time() - 42000,
              $params['path'],
              $params['domain'],
              $params['secure'],
              $params['httponly']
          );
      }

      // Finally destroy the session
      session_destroy();

      static::forgetLogin();
    }

    /**
     * Remember the originally-requested page in the session
     *
     * @return void
     */
    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
        
        return $_SESSION['return_to'];
    }

    /**
     * Get the originally-requested page to return to after requiring login, or default to the homepage
     *
     * @return void
     */
    public static function getReturnToPage()
    {
        $_SESSION['return_to'] ?? '/start';
    }

    /**
     * Keep history visited pages
     * 
     * @return array History of session
     */
    /*public static function keepHistory()
    {
        $_SESSION["history"] = header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $history=$_SESSION["history"];
    
        $history[]=$_SERVER["PHP_SELF"];
    
        $_SESSION["history"]=$history;
    
        return $_SESSION["history"];
    }*/

    /**
     * Get the previous page to return to after requiring login, or default to the homepage
     *
     * @return void
     */
    /*public static function getTwoPagesBack()
    {
        $history = static::keepHistory();
        var_dump($history);
        exit;
        header("Location : $history[2]", TRUE, 307);
    }*/

    /**
     * Get the previous page to return to after requiring login, or default to the homepage
     *
     * @return void
     */
    public static function getPreviousPage()
    {
        header('Location:' . $_SERVER['HTTP_REFERER'], TRUE, 307);
    }
    
    /**
     * Get the current logged-in user, from the session or the remember-me cookie
     *
     * @return mixed The user model or null if not logged in
     */
    public static function getUser()
    {
        if (isset($_SESSION['user_id'])) {

            return User::findByID($_SESSION['user_id']);

        } else {

            return static::loginFromRememberCookie();

        }
    }

    /**
     * Login the user from a remembered login cookie
     *
     * @return mixed The user model if login cookie found; null otherwise
     */
    protected static function loginFromRememberCookie()
    {
        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {

            $remembered_login = RememberedLogin::findByToken($cookie);

            if ($remembered_login  && ! $remembered_login->hasExpired()) {

                $user = $remembered_login->getUser();

                static::login($user, false);

                return $user;
            }
        }
    }

    /**
     * Forget the remembered login, if present
     * 
     * @return void
     */
    protected static function forgetLogin() {
        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {
            $remembered_login = RememberedLogin::findByToken($cookie);

            if ($remembered_login) {
                $remembered_login->delete();
            }

            setcookie('remember_me', '', time() - 3600);  // set to expire in the past
        }
    }
}
