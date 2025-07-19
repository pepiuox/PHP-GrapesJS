<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class formCRUD {

    protected $conn;
    public $tname;

    public function __construct($conn) {

        $this->conn = $conn;
        $this->tname = '';
    }

    public function protect($str) {
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlentities($str, ENT_QUOTES);
        $str = htmlspecialchars(trim($str), ENT_QUOTES);
        $str = mysqli_real_escape_string($this->conn, $str);
        return $str;
    }

    public function tableSetting($tname) {
        $sql = "SELECT * FROM table_settings WHERE table_name=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $tname);
        $stmt->execute();
        $rts = $stmt->get_result();
        $nm = $rts->num_rows;
        if ($nm > 0) {
            $row = $rts->fetch_assoc();
            $row['table_list'];
            $row['table_view'];
            $row['table_add'];
            $row['table_update'];
            $row['table_delete'];
            $row['table_secure'];
        } else {
            echo '';
        }
    }
}
