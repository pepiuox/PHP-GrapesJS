<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
/**
 * Description of UserVerify
 *
 * @author PePiuoX
 */
class UserVerify {

    protected $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
// Require credentials for DB connection.
        if (isset($_GET['id']) && isset($_GET['code']) && isset($_GET['hash'])) {
            $_SESSION['id'] = $_GET['id'];
            $_SESSION['code'] = $_GET['code'];
            $_SESSION['hash'] = $_GET['hash'];
        }

        if (isset($_POST['bverify'])) {
            $this->Verify();
        }
    }

    /*
     * Function Verify(){
     * User e-mail verification on verify.php
     * E-mail and activation code are cross-referenced with database, if both are correct
     * is_activated is updated in database.
     */
    /**/

    private function Verify() {

        if (isset($_POST['bverify'])) {
            if (!empty($_POST['id']) && !empty($_POST['code']) && !empty($_POST['hash'])) {
                $id = $_SESSION['id'];
                $code = $_SESSION['code'];
                $hash = $_SESSION['hash'];
// Variables for Verify()
                $user_email = htmlentities($_POST['id']);
                $act_code = htmlentities($_POST['code']);
                $hash_code = htmlentities($_POST['hash']);
                if ($id === $user_email && $code === $act_code && $hash === $hash_code) {

// Cross-reference e-mail and activation_code in database with values from URL.
                    $stmt = $this->connection->prepare("SELECT * FROM uverify WHERE email = ? AND mkhash = ? AND activation_code = ?");
                    $stmt->bind_param("sss", $user_email, $hash_code, $act_code);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 0) {
                        $_SESSION['ErrorMessage'] = 'Error in verifying the activation of your account! ';
                    }
                    $urw = $result->fetch_assoc();
                    $stmt->close();

                    $uid = $urw['iduv'];

                    function encKey($len = 64) {
                        return substr(sha1(openssl_random_pseudo_bytes(17)), - $len);
                    }

                    $mhash = encKey();

                    $verified = 1;
                    $bann = 0;
                    $status = 1;
                    $cclean = 'NULL';

                    $stmt1 = $this->connection->prepare("UPDATE uverify SET mkhash = ?, activation_code = ?, is_activated = ?, banned = ?  WHERE iduv = ?");
                    $stmt1->bind_param("ssiis", $mhash, $cclean, $verified, $bann, $uid);
                    $stmt1->execute();
                    $res1 = $stmt1->affected_rows;
                    $stmt1->close();

                    $stmt2 = $this->connection->prepare("UPDATE users SET verified = ?, status = ?, email_verified = ? WHERE idUser = ?");
                    $stmt2->bind_param("iiss", $verified, $status, $cclean, $uid);
                    $stmt2->execute();
                    $res2 = $stmt2->affected_rows;
                    $stmt->close();

                    if ($res1 === 1 && $res2 === 1) {
                        $_SESSION['SuccessMessage'] = 'Error in verifying the activation of your account! ';
                        header('Location: login.php');
                    } else {
                        $_SESSION['ErrorMessage'] = 'Error in verifying the activation of your account! ';
                    }
                }
            } else {
                $_SESSION['ErrorMessage'] = 'Error in verifying the activation of your account, please contact support! ';
            }
        }
    }

    /* End Verify() */

    private function UpdateVerifyU($uid, $hash_code, $act_code) {


        $mhash = $this->encKey();
        $verified = 1;
        $bann = 0;
        $cclean = '';
        $stmt = $this->connection->prepare("UPDATE uverify SET mkhash = ?, is_activated = ?, banned = ? activation_code = ? WHERE iduv = ? AND mkhash = ? AND  activation_code = ?");
        $stmt->bind_param("siissss", $mhash, $verified, $bann, $cclean, $uid, $hash_code, $act_code);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            $this->UpdateUserV($uid, $act_code);
        } else {
            $_SESSION['ErrorMessage'] = 'Error in verifying the activation of your account! ';
        }
        $stmt->close();
    }

    private function UpdateUserV($uid, $act_code) {

        $verified = 1;
        $status = 1;
        $cclean = '';
        $stmt = $this->connection->prepare("UPDATE users SET verified = ?, status = ?, email_verified = ? WHERE idUser = ? AND  email_verified = ?");
        $stmt->bind_param("iisss", $verified, $status, $cclean, $uid, $act_code);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            header('Location: verify.php');
            exit;
        } else {
            $_SESSION['ErrorMessage'] = 'Error in verifying the activation of your account! ';
        }
        $stmt->close();
    }
}
