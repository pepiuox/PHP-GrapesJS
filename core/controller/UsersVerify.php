<?php
//
//  This application develop by PePiuoX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * Description of UserVerify
 *
 * @author PePiuoX
 */
class UsersVerify {

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
	
    private function includes(){
        require_once "../PHPMailer/src/Exception.php";
        require_once "../PHPMailer/src/PHPMailer.php";
        require_once "../PHPMailer/src/SMTP.php";
    }

    /*
     * Function uVerify(){
     * User e-mail verification on verify.php
     * E-mail and activation code are cross-referenced with database, if both are correct
     * is_activated is updated in database.
     */
    /**/

    private function uVerify() {
        if (isset($_POST['bverify'])) {
            if (!empty($_POST['code']) && !empty($_POST['hash'])) {

// Variables for uVerify()
                $act_code = $this->pt->secureStr($_POST['code']);
                $hash_code = $this->pt->secureStr($_POST['hash']);
		
                if ($this->code === $act_code && $this->hash === $hash_code) {
                    		    
// Cross-reference e-mail and activation_code in database with values from URL.
                    $stmt = $this->conn->prepare("SELECT iduv, usercode, email, usr_type FROM uverify WHERE mkhash = ? AND activation_code = ?");
                    $stmt->bind_param("ss", $hash_code, $act_code);
                    $stmt->execute();
                    $result = $stmt->get_result();
					$stmt->close();
					
                    if ($result->num_rows === 1) {
                        
                        $urw = $result->fetch_assoc();                       
                        $uid = $urw['iduv'];
						$uscod = $urw['usercode'];
						$username = $urw['username'];
						$email = $urw['email'];
						$usrtype = $urw['usr_type']; 
						if($usrtype === 'cliente'){
							$valusr = '3';
						}elseif($usrtype === 'servicios'){
							$valusr = '5';
						}elseif($usrtype === 'productor'){
							$valusr = '7';
						}
						

						$astmt = $this->conn->prepare("SELECT usercode, action, validation FROM users_actions WHERE usercode = ? AND validation = ?");
						$astmt->bind_param("ss", $uscod, $act_code);
						$astmt->execute();
						$aresult = $astmt->get_result();
						$astmt->close();
						$urac = $aresult->fetch_assoc();
					
					    if($urac['action'] === 'validation'){
				
							$mhash = $this->gc->randHash();
							$cchng = $this->gc->getIdCode();
							$apr = $this->gc->getRandKey();
							$ver = $this->gc->getIdCode();
							$folder = $this->gc->randLengthString(19);
							$verified = 1;
							$bann = 0;
							$status = 1;
                        
							$stmt1 = $this->conn->prepare("UPDATE uverify SET mkhash = ?, activation_code = ?, is_activated = ?, banned = ?  WHERE iduv = ?  AND usercode = ? AND mkhash = ?");
							$stmt1->bind_param("ssiisss", $mhash, $cchng, $verified, $bann, $uid, $uscod, $this->hash);
							$stmt1->execute();
							$res1 = $stmt1->affected_rows;
							$stmt1->close();

							$stmt2 = $this->conn->prepare("UPDATE users SET verified = ?, status = ?, email_verified = ? WHERE idUser = ? AND usercode = ?");
							$stmt2->bind_param("iisss", $verified, $status, $cchng, $uid, $uscod);
							$stmt2->execute();
							$res2 = $stmt2->affected_rows;
							$stmt2->close();
 
							$stmt3 = $this->conn->prepare("UPDATE users_profiles SET mkhash = ? WHERE idp = ? AND usercode = ? AND mkhash = ?");
							$stmt3->bind_param("ssss", $mhash, $uid, $uscod, $this->hash);
							$stmt3->execute();
							$res3 = $stmt3->affected_rows;
							$stmt3->close();
			
							$stmt4 = $this->conn->prepare("UPDATE users_info SET active = ? WHERE userid = ? AND usercode = ?");
							$stmt4->bind_param("iss", $verified, $uid, $uscod);
							$stmt4->execute();
							$res4 = $stmt4->affected_rows;
							$stmt4->close();
							
							$stmt5 = $this->conn->prepare("UPDATE users_types SET user_type = ?, val_user = ? WHERE usercode = ?");
							$stmt5->bind_param("sis", $usrtype, $valusr, $uscod);
							$stmt5->execute();
							$res5 = $stmt5->affected_rows;
							$stmt5->close();
						
							$this->uca->UpActions($uscod, $cchng, $ver, $apr);
							$this->uca->UpActive($uscod, $verified);			
							$this->uca->UpPlans($uscod, $verified);
							$this->uca->UpPrivacy($uscod, $uid, $ver);
							$this->uca->UpSecures($uscod, $uid, $folder, $cchng);
							$this->uca->UpVerify($uscod, $ver);			
			 
							if ($res1 === 1 && $res2 === 1 && $res3 === 1 && $res4 === 1 && $res5 === 1) {
								
								unset($_SESSION['vcode']);
								unset($_SESSION['usr']);
								$this->sendPMailer($email, $username);
								$_SESSION['SuccessMessage'] = 'Success when verifying the activation of your account! ';
								header('Location: login.php');

							} else {
								$_SESSION['ErrorMessage'] = 'Error in verifying the activation of your account! ';
							}
						}else {
                            $_SESSION['ErrorMessage'] = 'Error in actions from activation of your account! ';
                        }
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
	private function sendPMailer($email, $username)
    {
		$usrml = $this->gc->ende_crypter(
            "decrypt",
            $email,
            SECURE_TOKEN,
            SECURE_HASH
        );
		$usrnm = $this->gc->ende_crypter(
            "decrypt",
            $username,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $usr = $this->gc->ende_crypter(
            "decrypt",
            USEREMAIL,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $pas = $this->gc->ende_crypter(
            "decrypt",
            PASSMAIL,
            SECURE_TOKEN,
            SECURE_HASH
        );

        $subject = "Su cuenta está verificada y activada.";
        // This should be changed to an email that you would like to send activation e-mail from.
        $body = "<html>
<body><p><b>Hola " . $usrnm . ".</b>";
        $body .=
            "<br>Tu cuenta está activada." .
            "<br>"; // Input the URL of your website.
        $body .= "Por favor crea tu frase de recuperación.</p></body></html>";
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = MAILSERVER; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = $usr; //SMTP username
        $mail->Password = $pas; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = PORTSERVER; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom($usr, SITE_NAME);
        $mail->addAddress($usrml, $usrnm);
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $mail->Body = $body;

        if (!$mail->send()) {
            echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
        } else {
            echo "<h4>The email message was sent. Thank you for registering</h4>";
        }
    }
}
