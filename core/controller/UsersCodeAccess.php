<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class UsersCodeAccess{
  protected $conn;
  private $actions;
  
    public function __construct(){
       global $conn;
        $this->conn = $conn;
        $this->actions = [
            'users_active',
            'users_plans',
            'users_privacy',
            'users_searches',
            'users_secures',
            'users_social_media',
            'users_types',
            'users_verifications'                   
            ]; 
              
    }
   public function AddUserCode($uscod){
        foreach($this->actions as $tb){
                $sql= "INSERT INTO ".$tb." (usercode) VALUES (?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $uscod);
                $stmt->execute();
				$stmt->close();
        }    
            
    }
    
     public function UpActions($uscod, $val, $ver, $apr){
        $naction ='verificated';
        $stmt = $this->conn->prepare("UPDATE users_actions SET action=?, validation=?, verification=?, approval=? WHERE usercode=?");
        $stmt->bind_param("sssss", $naction, $val, $ver, $apr, $uscod);
        $stmt->execute();
        $stmt->close();
    }
	
    public function UpActive($uscod, $verst){
	$stmt = $this->conn->prepare("UPDATE users_active SET is_active=? WHERE usercode=?)");
        $stmt->bind_param("is", $verst, $uscod);
        $stmt->execute();
        $stmt->close();
    }
	
    public function UpSecures($uscod, $ids, $val, $folder){
        $stmt = $this->conn->prepare("UPDATE users_secures SET idSec=?, folder_files=?, validation=? WHERE usercode=?");
        $stmt->bind_param("ssss", $ids, $folder, $val, $uscod);
        $stmt->execute();
        $stmt->close();
    }
	
    public function UpPrivacy($uscod,$idp, $ver){
        $stmt = $this->conn->prepare("UPDATE users_privacy SET idPri=?, verification=? WHERE usercode=?");
        $stmt->bind_param("sss",$idp, $ver, $uscod);
        $stmt->execute();
        $stmt->close();
    }
    
    public function UpVerify($uscod, $ver){
        $stmt = $this->conn->prepare("UPDATE users_verifications SET verification=? WHERE usercode=?)");
        $stmt->bind_param("ss", $ver, $uscod);
        $stmt->execute();
        $stmt->close();
    }
    
    public function UpPlans($uscod, $verst){
	$stmt = $this->conn->prepare("UPDATE users_plans SET verification=? WHERE usercode=?)");
        $stmt->bind_param("is", $verst, $uscod);
        $stmt->execute();
        $stmt->close();
    }
}
