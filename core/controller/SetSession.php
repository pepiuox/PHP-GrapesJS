<?php

class SessionClass {

    protected $conn;
    private static $_instance;

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

// getInstance

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        setsession_set_save_handler(
                array($this, "open"), array($this, "close"),
                array($this, "read"), array($this, "write"),
                array($this, "destroy"), array($this, "gc")
        );

        $createTable = "CREATE TABLE IF NOT EXISTS `setsession`( " .
                "`ssID` VARCHAR(128), " .
                "`data` MEDIUMBLOB, " .
                "`timestamp` INT, " .
                "`ip` VARCHAR(20), " .
                "PRIMARY KEY (`ssID` ), " .
                "KEY (`timestamp`, `ssID`))";

        $this->conn->query($createTable);
    }

// construct



    public function __destruct() {
        setsession_write_close();
    }

    public function open($path, $id) {
        // do nothing
        return (true);
    }

    public function close() {
        // do nothing
        return (true);
    }

    public function read($id) {
        $escapedID = mysqli_escape_string($id);
        $query = sprintf("SELECT * FROM setsession WHERE ssID = '%s'", $escapedID);
        $res = $this->conn->query($query);

        if ((!$res) || (!mysqli_num_rows($res))) {
            $timestamp = time();
            $query = sprintf("INSERT INTO setsession (ssID, timestamp) VALUES ('%s', %s)", $escapedID, $timestamp);
            $this->conn->query($query);
            return '';
        } elseif (($row = mysqli_fetch_assoc($res))) {
            $query = "UPDATE setsession SET timestamp = ";
            $query .= time();
            $query .= sprintf(" WHERE ssID = '%s'", $escapedID);
            $this->conn->query($query);
            return $row['data'];
        } // elseif

        return "";
    }

// read

    public function write($id, $data) {
        $query = "REPLACE INTO setsession (ssID, data, ip, timestamp) ";
        $query .= sprintf("VALUES ('%s', '%s', '%s', %s)",
                mysqli_escape_string($id), mysqli_escape_string($data),
                $_SERVER['REMOTE_ADDR'], time());
        $this->conn->query($query);
        return (true);
    }

// write

    public function destroy($id) {
        $escapedID = mysqli_escape_string($id);
        $query = sprintf("DELETE FROM setsession WHERE ssID = %s", $escapedID);
        $res = $this->conn->query($query);
        return (mysqli_affected_rows($res) == 1);
    }

// destroy

    public function gc($lifetime) {
        $query = "DELETE FROM setsession WHERE ";
        $query = sprintf("%s - timestamp > %s", time(), $lifetime);
        $this->conn->query($query);
        return (true);
    }

// gc
}

// SessionClass
