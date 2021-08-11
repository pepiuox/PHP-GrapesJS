<?php

class AccessConnect {

    public $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
    }

    public function getUserInfo($username) {
        $q = "SELECT * FROM uverify WHERE username = '$username'";
        $result = $this->connection->query($q);
        /* Error occurred, return given name by default */
        if (!$result || ($result->num_rows < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = $result->fetch_array();
        return $dbarray;
    }

    public function getUserOnly($username) {
        $q = "SELECT username FROM uverify WHERE username = '$username'";
        $result = $this->connection->query($q);
        /* Error occurred, return given name by default */
        if (!$result || ($result->num_rows < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = $result->fetch_assoc();
        return $dbarray;
    }

}

?>