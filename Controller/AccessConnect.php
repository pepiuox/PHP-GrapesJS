<?php

class AccessConnect {

    protected $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
    }

    public function getUserInfo($username) {
        $stmt = $this->connection->prepare("SELECT iduv, email, level  FROM uverify WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            /* Return result array */
            $dbarray = $result->fetch_assoc();
            return $dbarray;
        } else {
            /* Error occurred, return given name by default */
            return NULL;
        }
        $stmt->close();
    }

    public function getUserOnly($username) {
        $stmt = $this->connection->prepare("SELECT username FROM uverify WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            /* Return result array */
            $dbarray = $result->fetch_assoc();
            return $dbarray['username'];
        } else {
            /* Error occurred, return given name by default */
            return NULL;
        }
        $stmt->close();
    }
}

?>
