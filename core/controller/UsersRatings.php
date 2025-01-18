<?php 
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class UsersRatings{
    protected $conn;
    public $date;
    protected $tble = 'users_likes';
    public $timestamp;
    protected $ucode;
    protected $fcode;
    public function __construct(){
        global $conn;
        $this->conn = $conn;
        $this->date = new Datetime();
        $this->ucode = $_SESSION['ucode'];
        $this->fcode = $_SESSION['ccode'];
        $this->timestamp = $this->date->fotmat('Y-m-d H:i:s');
        if (isset($_POST["like"])) {
            $this->CheckRatings();
        }
        if (isset($_POST["unfollow"])) {
            $this->unlikeUsr();
        }
    }
    private function checkRatings(){
        if (isset($_POST["like"])) {
            $query = "SELECT usercode, fusercode FROM ".$this->tble." WHERE usercode=? AND lusercode=?";
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
    private function RatingUsr(){  
        $query = "INSERT INTO ".$this->tble." (usercode,lusercode,timestamp)VALUES (?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $this->ucode,$this->fucode, $this->timestamp);
        $stmt->execute();
		$stmt->close();
    }
    private function unRatingUsr(){   
        if (isset($_POST["unlike"])) {
            $query = "DELETE ".$this->tble." WHERE usercode=? AND lusercode=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $this->ucode,$this->fucode);
            $stmt->execute();
			$stmt->close();
        }
    }
}
?>
