<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class UsersActions{
    protected $conn;
    protected $ucode;
    protected $tble = 'users_actions';
    
    public function __construct(){
        global $conn;
        $this->conn = $conn;
        $this->ucode = $_SESSION["access_id"];
    }
    private function uActions(){
        $sql = "SELECT * FROM ".$this->tble." WHERE usercode = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->ucode);
        $stmt->execute();
        $result = $stmt->get_result();
		$stmt->close();
        if ($result->num_rows == 1) {
            /* Return result array */
            $dbarray = $result->fetch_assoc();
            return $dbarray;
        } else {
            /* Error occurred, return given name by default */
            return NULL;
        }     
    }
}
