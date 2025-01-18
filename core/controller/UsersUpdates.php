<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class UsersUpdates{
	
    protected $conn;
    private $uca;
    public $gc;
	private $pt;
    private $code;
    private $hash;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
		$this->uca = new UsersCodeAccess();
        $this->gc = new GetCodeDeEncrypt();
		$this->pt = new Protect();
		// Require credentials for DB connection.
        if (isset($_GET['vcode']) && isset($_GET['usr'])) {
            $this->code = $_SESSION['vcode'] = $this->pt->secureStr($_GET['vcode']);
            $this->hash = $_SESSION['urs'] = $this->pt->secureStr($_GET['usr']);
        }

        if (isset($_POST['bverify'])) {
            $this->uVerify();
        }
       $this->includes();
    }
	
	private function UpdateUverify($uid, $hash_code, $act_code) {

        $mhash = $this->gc->randHash();
        $verified = 1;
        $bann = 0;
        $cchng = '';
        $stmt = $this->conn->prepare("UPDATE uverify SET mkhash = ?, is_activated = ?, banned = ? activation_code = ? WHERE iduv = ? AND mkhash = ? AND  activation_code = ?");
        $stmt->bind_param("siissss", $mhash, $verified, $bann, $cchng, $uid, $hash_code, $act_code);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
           $up = $this->UpdateProfiles($uid, $mhash);
           $uv = $this->UpdateUsers($uid, $act_code);
           if($up == true && $uv == true){
               header('Location: verify.php');
            exit;
           }
        } else {
            $_SESSION['ErrorMessage'] = 'Error in verifying the activation of your account! ';
        }
        $stmt->close();
    }
private function UpdateProfiles($uid, $mhash) {

        $stmt = $this->conn->prepare("UPDATE users_profiles SET mkhash = ? WHERE idp = ? AND  email_verified = ?");
        $stmt->bind_param("sss", $mhash, $uid);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            return true;
        } else {
            return false;
        }
        
    }
    private function UpdateUsers($uid, $act_code) {

        $verified = 1;
        $status = 1;
        $cchng = '';
        $stmt = $this->conn->prepare("UPDATE users SET verified = ?, status = ?, email_verified = ? WHERE idUser = ? AND  email_verified = ?");
        $stmt->bind_param("iisss", $verified, $status, $cchng, $uid, $act_code);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            return true;
        } else {
           return false;
        }
        $stmt->close();
    }
}
