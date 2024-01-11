<?php
class CheckUsersSession
{
	protected $conn;
    private $user;
    private $hash;
	private $expiry;
    public function __construct()
    {
		global $conn;
        $this->conn = $conn;
		$this->expiry = time() + 3600;
        if (isset($_SESSION["user_id"]) && isset($_SESSION["hash"])) {
            $this->user = $_SESSION["user_id"];
            $this->hash = $_SESSION["hash"];
            $this->CheckUsers();
        }
    }
    private function CheckUsers()
    {
        //Select data in table uverify
        $stmt = $this->conn->prepare(
            "SELECT idUser, mkhash FROM users WHERE idUser = ? AND mkhash = ?"
        );
        $stmt->bind_param("ss", $this->iduv, $this->hash);
        $stmt->execute();
        $check = $stmt->get_result();
        $stmt->close();
        if ($check->num_rows === 0) {
			if (isset($_COOKIE["cookname"]) && isset($_COOKIE["cookid"])) {
				unset($_COOKIE['cookname']);
				unset($_COOKIE['cookid']);
                    setcookie("cookname", "", time() - $this->expiry, "/");
                    setcookie("cookid", "", time() - $this->expiry, "/");
                }
                $_SESSION = [];
                /* Unset PHP session variables */
				unset($_SESSION["access_id"]);
                unset($_SESSION["username"]);
                unset($_SESSION["user_id"]);
                unset($_SESSION["level"]);
                unset($_SESSION["hash"]);
                unset($_SESSION);
                session_destroy(); // Destroy all session data.				
            header("Location: login.php");
            exit;
        }
    }
}
?>
