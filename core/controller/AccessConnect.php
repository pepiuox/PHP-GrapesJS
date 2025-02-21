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

    /* get number of visitor
     *
     */

    private function activeGuests() {
        return $this->conn->query("SELECT ip FROM active_guests")->num_rows;
    }

    public function numVisitor() {
        return $this->activeGuests();
    }

    /* get number of users
     *
     */

    private function verifiedUser() {
        $ver = 1;
        $stmt = $this->conn->prepare("SELECT verified FROM users WHERE verified = ?");
        $stmt->bind_param("i", $ver);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows;
    }

    public function numUsers() {
        return $this->verifiedUser();
    }

    public function getUserInfo($username) {
        $stmt = $this->conn->prepare(
            "SELECT iduv, email, level  FROM uverify WHERE username = ?"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows == 1) {
            /* Return result array */
            $dbarray = $result->fetch_assoc();
            return $dbarray;
        } else {
            /* Error occurred, return given name by default */
            return null;
        }
    }

    public function getUserOnly($username) {
        $stmt = $this->conn->prepare(
            "SELECT username FROM uverify WHERE username = ?"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows == 1) {
            /* Return result array */
            $dbarray = $result->fetch_assoc();
            return $dbarray["username"];
        } else {
            /* Error occurred, return given name by default */
            return null;
        }
    }
}

?>
