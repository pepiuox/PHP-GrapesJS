<?php

class formCRUD {

    protected $connection;
    public $tname;

    public function __construct($conn) {

        $this->connection = $conn;
        $this->tname = '';
    }

    public function protect($str) {
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlentities($str, ENT_QUOTES);
        $str = htmlspecialchars(trim($str), ENT_QUOTES);
        $str = mysqli_real_escape_string($this->connection, $str);
        return $str;
    }

    public function tableSetting($tname) {
        $sql = "SELECT * FROM table_settings WHERE table_name=?";
        $stmt = $this->connection->prepare($sql);
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
