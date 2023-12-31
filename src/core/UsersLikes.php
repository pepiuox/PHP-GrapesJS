<?php 
class UsersLikes{
    protected $conn;
    public $date;
    protected $tble = 'users_likes';
    public $timestamp;
    protected $ucode;
    protected $lcode;
    public function __construct(){
        global $conn;
        $this->conn = $conn;
        $this->date = new Datetime();
        $this->ucode = $_SESSION["access_id"];
        $this->lcode = $_SESSION['ccode'];
        $this->timestamp = $this->date->fotmat('Y-m-d H:i:s');
        if (isset($_POST["follow"])) {
            $this->CheckLike();
        }
        if (isset($_POST["unfollow"])) {
            $this->unlikeUsr();
        }
    }
    private function CheckLike(){
        if (isset($_POST["like"])) {
            $query = "SELECT usercode, fusercode FROM ".$this->tble." WHERE usercode=? AND lusercode=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $this->ucode,$this->lcode);
            $stmt->execute();
            $result = $stmt->get_result();
			$stmt->close();
            if($result->num_rows == 0){
                $this->FollowUsr();
            }        
        }
    }
    private function LikeUsr(){  
        $query = "INSERT INTO ".$this->tble." (usercode,lusercode,timestamp)VALUES (?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $this->ucode,$this->lcode, $this->timestamp);
        $stmt->execute();
		$stmt->close();
    }
    private function unLikeUsr(){   
        if (isset($_POST["unlike"])) {
            $query = "DELETE ".$this->tble." WHERE usercode=? AND lusercode=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $this->ucode,$this->lcode);
            $stmt->execute();
			$stmt->close();
        }
    }
}
?>
