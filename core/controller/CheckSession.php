<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class CheckSession {

    protected $conn;
    private $session;
    private $access;

    public function __construct() {
        global $conn;
        $this->conn = $conn;

        $this->checking();
    }

    private function sessionKey($len = 32) {
        $bytes = random_bytes(16);
        return bin2hex($bytes) . substr(sha1(openssl_random_pseudo_bytes(13)), - $len);
    }

    private function accessKey($len = 32) {
        $bytes = random_bytes(16);
        return bin2hex($bytes) . substr(sha1(openssl_random_pseudo_bytes(27)), - $len);
    }

    /**
     * Checks and manages the client session.
     *
     * This function verifies the presence of a client session in cookies and sessions.
     * If a session cookie exists but no session is active, it attempts to verify the session
     * against the database and create a new access key if the session is not found.
     * It updates the session and cookie values accordingly. If neither session nor cookie is present,
     * it generates a new session key and sets a session cookie.
     */

    public function checking() {
        if (isset($_COOKIE['client_session']) && !empty($_COOKIE['client_session'])) {
            $this->session = $_COOKIE['client_session'];
            if (!isset($_SESSION['client_session']) && empty($_SESSION['client_session'])) {

                $query = $this->conn->prepare("SELECT * FROM active_sessions WHERE session=:session");
// sanitize
                $this->session = htmlspecialchars(strip_tags($this->session));
                // bind value
                $query->bindParam(":session", $this->session);
// execute query
                $query->execute();
// count result
                $result = $query->rowCount();

                if ($result === 0) {
                    $this->access = $this->accessKey();
                    $cooksess = $this->conn->prepare("INSERT INTO active_sessions SET session=:session, access=:access");
// sanitize
                    $this->session = htmlspecialchars(strip_tags($this->session));
                    $this->access = htmlspecialchars(strip_tags($this->access));
// bind value
                    $cooksess->bindParam(":session", $this->session);
                    $cooksess->bindParam(":access", $this->access);
// execute query
                    $cooksess->execute();
                    $_SESSION['client_session'] = $this->session;
                }
            } else {
                if ($_SESSION['client_session'] != $_COOKIE['client_session']) {
                    unset($_COOKIE['client_session']);
                    unset($_SESSION['client_session']);

                    $nval = $this->sessionKey();

                    setcookie('client_session', $nval, time() + 21600, "/");
                    // make a session 
                }
            }
        } else {
            $nval = $this->sessionKey();

            setcookie('client_session', $nval, time() + 21600, "/");
        }
    }

}