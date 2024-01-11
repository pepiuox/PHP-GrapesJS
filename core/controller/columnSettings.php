<?php

class columnSettings {

    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function setList($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setView($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setAdd($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setUpdate($value) {
        if ($value === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function colSettings($tname, $cname) {

        $stmt = $this->conn->prepare("SELECT * FROM table_column_settings WHERE name_table=? AND col_name=?");
        $stmt->bind_param("ss", $tname, $cname);
        $stmt->execute();
        $rts = $stmt->get_result();

        $nm = $rts->num_rows;

        if ($nm > 0) {
            $row = $rts->fetch_assoc();
            return json_encode($row, true);
        } else {
            echo 'Error in Database';
        }
        $stmt->close();
    }
}
?>
