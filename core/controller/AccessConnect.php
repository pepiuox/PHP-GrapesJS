<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class AccessConnect
{
    protected $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /* get number of visitor
     *
     */

    public function numVisitor()
    {
        return $this->conn->query("SELECT ip FROM active_guests")->num_rows;
    }

    /* get number of users
     *
     */

    public function numUsers()
    {
        return $this->conn->query(
            "SELECT verified FROM users WHERE verified='1'"
        )->num_rows;
    }

    public function getUserInfo($username)
    {
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

    public function getUserOnly($username)
    {
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
