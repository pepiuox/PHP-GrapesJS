<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class UsersPlans{
  protected $conn;
  protected $oneweek = 7;
  protected $onemonth = 30;
  protected $treemonth = 90;
  protected $ucode;
  protected $tble = 'users_plans';
  
  public function __construct(){
        global $conn;
        $this->conn = $conn;
        $this->ucode = $_SESSION['access_id'];
  }
    
  private function uPlans(){
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
