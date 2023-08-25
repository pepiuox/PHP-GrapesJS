<?php

class tableSettings {

    private $connection;

    public function __contruct($db) {
        $this->connection = $db;
    }

    public function checkList($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkView($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkAdd($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkUpdate($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkDelete($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkSecure($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function tbltSettings($tbname) {
        $sql = "SELECT * FROM table_settings WHERE table_name=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $tbname);
        $stmt->execute();
        $rts = $stmt->get_result();
        $nm = $rts->num_rows;
        if ($nm > 0) {
            $row = $rts->fetch_assoc();
            $this->checkList($row['table_list']);
            $this->checkView($row['table_view']);
            $this->checkAdd($row['table_add']);
            $this->checkUpdate($row['table_update']);
            $this->checkDelete($row['table_delete']);
            $this->checkSecure($row['table_secure']);
        } else {
            echo 'Error in Database';
        }
    }
}

?>
