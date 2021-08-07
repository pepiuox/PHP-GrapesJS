<?php

class AccessConnect {
    /* MySQLi Procedural */

    public $connection;
    var $link;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
    }

    public function getUserInfo($username) {
        $q = "SELECT * FROM " . TBL_USERS . " WHERE username = '$username'";
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
        $q = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = $this->connection->query($q);
        /* Error occurred, return given name by default */
        if (!$result || ($result->num_rows < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = $result->fetch_array();
        return $dbarray;
    }

}

?>