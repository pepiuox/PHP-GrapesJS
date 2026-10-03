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
 * Cambio de PIN con verificación de passphrase y encriptación.
 */
class ChangePIN
{
    public string $baseurl;
    protected PDO $conn;
    private int $iduv;
    private string $ucode;
    private string $usname;
    private string $level;
    private string $mkhash;
    private GetCodeDeEncrypt $gc;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->gc = new GetCodeDeEncrypt();
        $this->baseurl = SITE_PATH;

        $this->iduv   = (int) ($_SESSION["user_id"] ?? 0);
        $this->ucode  = $_SESSION["access_id"] ?? '';
        $this->usname = $_SESSION["username"] ?? '';
        $this->level  = $_SESSION["levels"] ?? '';
        $this->mkhash = $_SESSION["hash"] ?? '';

        if (isset($_POST["changePIN"])) {
            $this->UpdatePIN();
        }

        $this->includes();
    }

    private function includes(): void
    {
        require_once URL . "/core/PHPMailer/src/Exception.php";
        require_once URL . "/core/PHPMailer/src/PHPMailer.php";
        require_once URL . "/core/PHPMailer/src/SMTP.php";
    }

    public function procheck(string $string): string
    {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    private function UpdatePIN(): void
    {
        $cemail = $this->procheck($_POST["email"] ?? '');
        $phrase = $this->procheck($_POST["recoveryphrase"] ?? '');
        $pin    = $this->procheck($_POST["pin"] ?? '');

        $umail = $this->gc->ende_crypter("encrypt", $cemail, SECURE_TOKEN, SECURE_HASH);
        $upin  = $this->gc->ende_crypter("encrypt", $pin, SECURE_TOKEN, SECURE_HASH);

        // Validación de usuario
        $stmt = $this->conn->prepare(
            "SELECT username, usercode, email, password, mktoken, mkkey, recovery_phrase
            FROM uverify
            WHERE iduv = :iduv AND usercode = :ucode AND email = :umail
            AND mkhash = :mkhash AND mkpin = :upin"
        );
        $stmt->execute([
            ':iduv'    => $this->iduv,
            ':ucode'   => $this->ucode,
            ':umail'   => $umail,
            ':mkhash'  => $this->mkhash,
            ':upin'    => $upin,
        ]);

        if ($stmt->rowCount() !== 1) {
            $_SESSION["ErrorMessage"] = "The passphrase or PIN is incorrect.";
            return;
        }

        $urw = $stmt->fetch(PDO::FETCH_ASSOC);

        $username    = $urw["username"];
        $usrcode     = $urw["usercode"];
        $email       = $urw["email"];
        $password    = $urw["password"];
        $secret_key  = $urw["mktoken"];
        $secret_iv   = $urw["mkkey"];
        $rvphrase    = $urw["recovery_phrase"];

        // Decrypt username
        $nusr = $this->gc->ende_crypter("decrypt", $username, SECURE_TOKEN, SECURE_HASH);

        // Encrypt recovery phrase con las llaves del usuario
        $dcrp = $this->gc->ende_crypter("encrypt", $phrase, $secret_key, $secret_iv);

        // Comparar datos
        if ($dcrp !== $rvphrase || $this->ucode !== $usrcode) {
            $_SESSION["ErrorMessage"] = "There was an error creating the new PIN code.";
            return;
        }

        // Generar nuevas llaves
        $ekey = $this->gc->randToken();
        $eiv  = $this->gc->randkey();
        $enck = $this->gc->randHash();

        // Encriptar datos con las nuevas llaves
        $nwusr = $this->gc->ende_crypter("encrypt", $nusr, $ekey, $eiv);
        $cml   = $this->gc->ende_crypter("encrypt", $cemail, $ekey, $eiv);
        $npass = $this->gc->ende_crypter("encrypt", $password, $ekey, $eiv);

        $newpin = random_int(100000, 999999);
        $nupin  = $this->gc->ende_crypter("encrypt", (string) $newpin, SECURE_TOKEN, SECURE_HASH);
        $nphr   = $this->gc->ende_crypter("encrypt", $phrase, $ekey, $eiv);

        // ✅ TRANSACCIÓN para atomicidad
        $this->conn->beginTransaction();
        try {
            // Update uverify
            $stmt1 = $this->conn->prepare(
                "UPDATE uverify
                SET password = :npass, mktoken = :ekey, mkkey = :eiv,
                mkhash = :enck, mkpin = :nupin, recovery_phrase = :nphr
                WHERE iduv = :iduv AND usercode = :ucode AND email = :umail
                AND mkhash = :mkhash AND mkpin = :upin"
            );
            $stmt1->execute([
                ':npass'   => $npass,
                ':ekey'    => $ekey,
                ':eiv'     => $eiv,
                ':enck'    => $enck,
                ':nupin'   => $nupin,
                ':nphr'    => $nphr,
                ':iduv'    => $this->iduv,
                ':ucode'   => $this->ucode,
                ':umail'   => $umail,
                ':mkhash'  => $this->mkhash,
                ':upin'    => $upin,
            ]);
            $inst1 = $stmt1->rowCount();

            // Update users
            $stmt2 = $this->conn->prepare(
                "UPDATE users
                SET username = :nwusr, email = :cml, password = :npass, mkpin = :nupin
                WHERE idUser = :iduv AND usercode = :ucode AND mkpin = :upin"
            );
            $stmt2->execute([
                ':nwusr'  => $nwusr,
                ':cml'    => $cml,
                ':npass'  => $npass,
                ':nupin'  => $nupin,
                ':iduv'   => $this->iduv,
                ':ucode'  => $this->ucode,
                ':upin'   => $upin,
            ]);
            $inst2 = $stmt2->rowCount();

            // Update users_profiles
            $stmt3 = $this->conn->prepare(
                "UPDATE users_profiles
                SET mkhash = :enck
                WHERE idp = :iduv AND usercode = :ucode AND mkhash = :mkhash"
            );
            $stmt3->execute([
                ':enck'   => $enck,
                ':iduv'   => $this->iduv,
                ':ucode'  => $this->ucode,
                ':mkhash' => $this->mkhash,
            ]);
            $inst3 = $stmt3->rowCount();

            if ($inst1 === 1 && $inst2 === 1 && $inst3 === 1) {
                $this->conn->commit();
                $this->sPMailer($username, $email, $newpin);
            } else {
                $this->conn->rollBack();
                $_SESSION["ErrorMessage"] = "Error in updated the new PIN code.";
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("ChangePIN Error: " . $e->getMessage());
            $_SESSION["ErrorMessage"] = "Database error. Please try again.";
        }
    }

    private function sPMailer(string $username, string $email, int $pin): void
    {
        $usrnm = $this->gc->ende_crypter("decrypt", $username, SECURE_TOKEN, SECURE_HASH);
        $usrml = $this->gc->ende_crypter("decrypt", $email, SECURE_TOKEN, SECURE_HASH);
        $usr   = $this->gc->ende_crypter("decrypt", USEREMAIL, SECURE_TOKEN, SECURE_HASH);
        $pas   = $this->gc->ende_crypter("decrypt", PASSMAIL, SECURE_TOKEN, SECURE_HASH);

        $subject = "Your PIN code is changed.";
        $body    = "<p>Hello <b>{$usrnm}</b>.</p>";
        $body   .= "<p>Your new PIN code is: <b>{$pin}</b></p>";
        $body   .= "<p>We recommend saving it. You do not need to access it with your password.</p>";
        $body   .= "<p>Remember to save your PIN code and recovery phrase always.</p>";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = MAILSERVER;
            $mail->SMTPAuth   = true;
            $mail->Username   = $usr;
            $mail->Password   = $pas;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = PORTSERVER;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($usr, SITE_NAME);
            $mail->addAddress($usrml, $usrnm);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            if ($mail->send()) {
                echo "<h4>The email was sent. Your new PIN code is: <b>{$pin}</b></h4>";
            } else {
                echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
            }
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            echo "<h4>Mailer Error. Please contact support.</h4>";
        }
    }
}
