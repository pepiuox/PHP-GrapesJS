<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class UsersRoles{
  protected $conn;
  protected $level;
  protected $tble = 'users_roles';
  
    public function __construct(){
        global $conn;
        $this->conn = $conn;
        $this->level = $_SESSION["levels"];
    }
       private function uRoles(){  
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->tble." WHERE name = ?");
        $stmt->bind_param("s", $this->level);
        $stmt->execute();
		$stmt->close();
        $result = $stmt->get_result();
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
