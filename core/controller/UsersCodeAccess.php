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
  private $secures;
  
    public function __construct(){
       global $conn;
        $this->conn = $conn;
        $this->actions = [
            'users_active',
            'users_plans',
            'users_searches',
            'users_social_media',
            'users_types',
            'users_verifications'                   
            ]; 

        $this->secures = [
            'users_privacy',
            'users_secures'                   
            ]; 
              
    }
    /**
     * Inserts a user code into multiple user-related tables.
     *
     * @param string $uscod The user code to be inserted into the tables.
     * 
     * Iterates over a predefined set of user-related tables and inserts 
     * the provided user code into each table. This function prepares 
     * and executes an SQL INSERT statement for each table.
     */

   public function AddUserCode($uscod){
        foreach($this->actions as $tb){
                $sql= "INSERT INTO ".$tb." (usercode) VALUES (?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $uscod);
                $stmt->execute();
		$stmt->close();
        }    
            
    }
    
    public function AddSecures($ids, $uscod){
        foreach($this->secures as $tb){
                $sql= "INSERT INTO ".$tb." (idUsr, usercode) VALUES (?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ss", $ids, $uscod);
                $stmt->execute();
		$stmt->close();
        }    
            
    }
    
    /**
     * Updates the user's actions table with new values.
     *
     * @param string $uscod The user code to identify the user.
     * @param string $val The validation status of the user.
     * @param string $ver The verification status of the user.
     * @param string $apr The approval status of the user.
     */
     public function UpActions($uscod, $val, $ver, $apr){
        $naction ='verificated';
        $stmt = $this->conn->prepare("UPDATE users_actions SET action=?, validation=?, verification=?, approval=? WHERE usercode=?");
        $stmt->bind_param("sssss", $naction, $val, $ver, $apr, $uscod);
        $stmt->execute();
        $stmt->close();
    }
	
    /**
     * Updates the user's active status in the users_active table.
     *
     * @param string $uscod The user code to identify the user.
     * @param int $verst The value to set the is_active field to.
     */
    public function UpActive($uscod, $verst){
	$stmt = $this->conn->prepare("UPDATE users_active SET is_active = ? WHERE usercode = ?");
        $stmt->bind_param("is", $verst, $uscod);
        $stmt->execute();
        $stmt->close();
    }
	
    /**
     * Updates the user's secures table with new values.
     *
     * @param string $uscod The user code to identify the user.
     * @param string $ids The id of the secure to be updated.
     * @param string $val The validation status of the secure.
     * @param string $folder The folder associated with the secure.
     */
    public function UpSecures($uscod, $ids, $val, $folder){
        $stmt = $this->conn->prepare("UPDATE users_secures SET idUsr=?, folder_files=?, validation=? WHERE usercode=?");
        $stmt->bind_param("ssss", $ids, $folder, $val, $uscod);
        $stmt->execute();
        $stmt->close();
    }
	
    public function UpPrivacy($uscod,$idp, $ver){
        $stmt = $this->conn->prepare("UPDATE users_privacy SET idUsr=?, verification=? WHERE usercode=?");
        $stmt->bind_param("sss",$idp, $ver, $uscod);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Updates the verification status in the users_verifications table.
     *
     * @param string $uscod The user code to identify the user.
     * @param string $ver The verification status to be updated.
     */

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
