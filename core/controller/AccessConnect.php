<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class AccessConnect {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /* get number of visitor */
    private function activeGuests() {
        $stmt = $this->conn->query("SELECT ip FROM active_guests");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($results);
    }

    public function numVisitor() {
        return $this->activeGuests();
    }

    /* get number of users */
    private function verifiedUser() {
        $ver = 1;
        $stmt = $this->conn->prepare("SELECT verified FROM users WHERE verified = ?");
        $stmt->execute([$ver]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($results);
    }

    public function numUsers() {
        return $this->verifiedUser();
    }

    public function getUserInfo($username) {
        $stmt = $this->conn->prepare(
            "SELECT iduv, email, level FROM uverify WHERE username = ?"
        );
        $stmt->execute([$username]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) == 1) {
            return $results[0];
        } else {
            return null;
        }
    }

    public function getUserOnly($username) {
        $stmt = $this->conn->prepare(
            "SELECT username FROM uverify WHERE username = ?"
        );
        $stmt->execute([$username]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) == 1) {
            return $results[0]["username"];
        } else {
            return null;
        }
    }
}
?>
