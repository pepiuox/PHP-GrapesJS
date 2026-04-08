<?php

// classes/SessionManager.php
class SessionManager {

    public static function startSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
    }

    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function getCurrentUser() {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }

    public static function hasRole($required_role) {
        if (!self::isLoggedIn()) {
            return false;
        }

        $user_role = $_SESSION['role'];
        $role_hierarchy = [
            'guest' => 1,
            'editor' => 2,
            'manager' => 3,
            'admin' => 4
        ];

        return isset($role_hierarchy[$user_role]) &&
                $role_hierarchy[$user_role] >= $role_hierarchy[$required_role];
    }

    public static function isAdmin() {
        return self::hasRole('admin');
    }

    public static function isManager() {
        return self::hasRole('manager');
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }
}

?>