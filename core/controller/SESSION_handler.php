<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class SESSION_handler {

    public static $gc_maxlifetime;
    public static $cookie_lifetime;

    public function __construct() {
        self::$gc_maxlifetime = ini_get('session.gc_maxlifetime');
        self::$cookie_lifetime = ini_get('session.cookie_lifetime');
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get($session_key) {
        if (session_status() == PHP_SESSION_NONE) {
            if (!empty($_SESSION[$session_key])) {
                $_SESSION['time_at_last_session'] = time();
                return $_SESSION[$session_key];
            }
        }
        return undefined;
    }

    public function set($session_key, $session_value) {
        if (session_status() == PHP_SESSION_NONE) {
            $_SESSION[$session_key] = $session_value;
            $_SESSION['time_at_last_session'] = time();
            return $_SESSION[$session_key];
        }
        return undefined;
    }

    public function session_expired() {
        $time = isset($_SESSION['time_at_last_session']) ?
                $_SESSION['time_at_last_session'] : 0;

        // same, but ugly:
        //$time = (int) @$_SESSION['time_at_last_session'];

        if (session_status() == PHP_SESSION_NONE) {
            return true; // session problem
        }
        if (time() - $time > self::$gc_maxlifetime) {
            return true; // session expired
        }
        // fix $_COOKIE[session_name() thing
        return false; // session is NOT expired
    }
}
?>

