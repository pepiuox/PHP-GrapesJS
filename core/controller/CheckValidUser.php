<?php

class CheckValidUser {

    protected $conn;
    private $id;
    private $hash;

    public function __construct() {      
        global $conn;
        $this->conn = $conn;
        if (isset($_SESSION['user_id']) && isset($_SESSION['hash'])) {
            $this->id = $_SESSION['user_id'];
            $this->hash = $_SESSION['hash'];

            $stmt = $this->conn->prepare("SELECT i.firstname, i.lastname, u.avatar, u.profile_image, u.profession, u.occupation FROM users_profiles u LEFT JOIN users_info i ON u.usercode = i.usercode WHERE u.idp = ? AND u.mkhash = ?");
            $stmt->bind_param("ss", $this->id, $this->hash);
            $stmt->execute();
            $result = $stmt->get_result();
            $nums = $result->num_rows;
            $urw = $result->fetch_assoc();
            $stmt->close();
            if ($nums === 1) {
                define('USERS_NAMES', $urw['firstname']);
                define('USERS_lASTNAMES', $urw['lastname']);
                define('USERS_FULLNAMES', $urw['firstname'] . ' ' . $urw['lastname']);
                define('USERS_AVATARS', $urw['avatar']);
                define('USERS_IMAGE', $urw['profile_image']);
                define('USERS_SKILLS', $urw['profession']);
                define('USERS_CURRENTS_OCCUPATION', $urw['occupation']);
                
            } else {
                unset($_SESSION['username']);
                unset($_SESSION['user_id']);
                unset($_SESSION['level']);
                unset($_SESSION['hash']);
                session_destroy(); // Destroy all session data.
            }
        } else {
            return;
            //header('Location: signin/login.php');
        }
    }
}
