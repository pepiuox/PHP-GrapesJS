<?php

class CheckValidUser {

    public function __construct() {
        global $conn;
        if (isset($_SESSION['user_id']) && isset($_SESSION['hash'])) {
            $id = $_SESSION['user_id'];
            $hash = $_SESSION['hash'];

            $stmt = $conn->prepare("SELECT * FROM profiles WHERE idp = ? AND mkhash = ?");
            $stmt->bind_param("ss", $id, $hash);
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
                define('USERS_SKILLS', $urw['profession']);
                define('USERS_CURRENTS_OCCUPATION', $urw['occupation']);
                define('USERS_IMAGE',$urw['profile_image']);
            } else {
                unset($_SESSION['user_id']);
                unset($_SESSION['level']);
                unset($_SESSION['hash']);
                session_destroy(); // Destroy all session data.
            }
        }else{
            return;
            //header('Location: signin/login.php');
        }
    }

}
