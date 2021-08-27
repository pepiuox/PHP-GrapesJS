<?php

class Ejecutor {

    public function AddData($tble) {
        global $conn;
        $sql = "SELECT * FROM $tble";
        return $conn->query($sql);
        while ($i < mysqli_num_fields($result)) {
            $meta = mysqli_fetch_field($result);
            $remp = str_replace("_", " ", $meta->name);
        }
    }

    public function UpdateData($table, $id) {
        
    }

    public function DeleteData($table, $id) {
        
    }

    public function searchAllDB($search) {
        global $conn;

        $out = "";

        $sql = "show tables";
        $rs = $conn->query($sql);
        if ($rs->num_rows > 0) {
            while ($r = $rs->fetch_array()) {
                $table = $r[0];
                $out .= $table . ";";
                $sql_search = "select * from " . $table . " where ";
                $sql_search_fields = Array();
                $sql2 = "SHOW COLUMNS FROM " . $table;
                $rs2 = $conn->query($sql2);
                if ($rs2->num_rows > 0) {
                    while ($r2 = $rs2->fetch_array()) {
                        $colum = $r2[0];
                        $sql_search_fields[] = $colum . " like('%" . $search . "%')";
                    }
                    $rs2->close();
                }
                $sql_search .= implode(" OR ", $sql_search_fields);
                $rs3 = $conn->query($sql_search);
                $out .= $rs3->num_rows . "\n";
                if ($rs3->num_rows > 0) {
                    $rs3->close();
                }
            }
            $rs->close();
        }

        return $out;
    }

}

?>