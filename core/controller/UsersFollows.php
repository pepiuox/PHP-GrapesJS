<?php 
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class UsersFollows{
    protected $conn;
    public $date;
    protected $tble = 'users_followers';
    public $timestamp;
    protected $ucode;
    protected $fcode;
    public function __construct(){
        global $conn;
        $this->conn = $conn;
        $this->date = new Datetime();
        $this->ucode = $_SESSION["access_id"];
        $this->fcode = $_SESSION['ccode'];
        $this->timestamp = $this->date->fotmat('Y-m-d H:i:s');
        if (isset($_POST["follow"])) {
            $this->ChechkFollower();
        }
        if (isset($_POST["unfollow"])) {
            $this->unFollowUsr();
        }
    }
    private function ChechkFollower(){
        if (isset($_POST["follow"])) {
            $query = "SELECT usercode, fusercode FROM ".$this->tble." WHERE usercode=? AND fusercode=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $this->ucode,$this->fucode);
            $stmt->execute();
            $result = $stmt->get_result();
			$stmt->close();
            if($result->num_rows == 0){
                $this->FollowUsr();
            }        
        }
    }
    private function FollowUsr(){  
        $query = "INSERT INTO ".$this->tble." (usercode,fusercode,timestamp)VALUES (?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $this->ucode,$this->fucode, $this->timestamp);
        $stmt->execute();
		$stmt->close();
    }
    private function unFollowUsr(){   
        if (isset($_POST["unfollow"])) {
            $query = "DELETE ".$this->tble." WHERE usercode=? AND fusercode=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $this->ucode,$this->fucode);
            $stmt->execute();
			$stmt->close();
        }
    }
}
?>
