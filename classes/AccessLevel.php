<?php

class AccessLevel {

    public function levels($id) {
        global $conn;
        $sql = "SELECT * FROM uverify WHERE username='$id'";
        $rest = $conn->query($sql);
        $level = $rest->fetch_assoc();
        return $level['level'];
    }

}

?>