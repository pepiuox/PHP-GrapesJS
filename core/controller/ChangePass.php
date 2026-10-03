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
 * Cambio de contraseña con verificación de passphrase.
 */
class ChangePass
{
    public string $baseurl;
    protected PDO $conn;
    private int $iduv;
    private string $ucode;
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
        $this->level  = $_SESSION["levels"] ?? '';
        $this->mkhash = $_SESSION["hash"] ?? '';

        if (isset($_POST["changePassword"])) {
            $this->ChangePassword();
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

    private function ChangePassword(): void
    {
        $vemail         = $this->procheck($_POST["email"] ?? '');
        $cpassword      = $this->procheck($_POST["cpassword"] ?? '');
        $recoveryphrase = $this->procheck($_POST["recoveryphrase"] ?? '');
        $password       = $this->procheck($_POST["password"] ?? '');
        $password2      = $this->procheck($_POST["password2"] ?? '');

        // Verificar que las contraseñas coincidan
        if ($password !== $password2) {
            $_SESSION["ErrorMessage"] = "Passwords do not match!";
            return;
        }

        $umail = $this->gc->ende_crypter("encrypt", $vemail, SECURE_TOKEN, SECURE_HASH);

        // Seleccionar datos de uverify
        $stmt = $this->conn->prepare(
            "SELECT iduv, usercode, username, email, password, mktoken, mkkey, mkpin, recovery_phrase
            FROM uverify
            WHERE iduv = :iduv AND usercode = :ucode AND email = :umail AND mkhash = :mkhash"
        );
        $stmt->execute([
            ':iduv'   => $this->iduv,
            ':ucode'  => $this->ucode,
            ':umail'  => $umail,
            ':mkhash' => $this->mkhash,
        ]);

        if ($stmt->rowCount() !== 1) {
            $_SESSION["ErrorMessage"] = "The data does not match to update your password.";
            return;
        }

        $dt = $stmt->fetch(PDO::FETCH_ASSOC);

        $duv    = $dt["iduv"];
        $srname = $dt["username"];
        $email  = $dt["email"];
        $chpss  = $dt["password"];
        $mkt    = $dt["mktoken"];
        $mkk    = $dt["mkkey"];
        $pin    = $dt["mkpin"];
        // 🐛 BUG CORREGIDO: antes decía "ecovery_phrase" (typo)
        $rcphr  = $dt["recovery_phrase"];

        // Decrypt username
        $uname = $this->gc->ende_crypter("decrypt", $srname, SECURE_TOKEN, SECURE_HASH);

        // Encrypt current password para comparar
        $cpass = $this->gc->ende_crypter("encrypt", $cpassword, $mkt, $mkk);

        if ($cpass !== $chpss) {
            $_SESSION["ErrorMessage"] = "The data does not match to update your password.";
            return;
        }

        // Generar nuevas llaves
        $ekey = $this->gc->randToken();
        $eiv  = $this->gc->randkey();
        $enck = $this->gc->randHash();

        // Encriptar con las nuevas llaves
        $nname   = $this->gc->ende_crypter("encrypt", $uname, $ekey, $eiv);
        $secpass = $this->gc->ende_crypter("encrypt", $password2, $ekey, $eiv);
        $cml     = $this->gc->ende_crypter("encrypt", $vemail, $ekey, $eiv);
        $rcp     = $this->gc->ende_crypter("encrypt", $recoveryphrase, $ekey, $eiv);

        // ✅ TRANSACCIÓN para atomicidad
        $this->conn->beginTransaction();
        try {
            // 🐛 BUG CORREGIDO: faltaba "SET" en la consulta original
            $stmt1 = $this->conn->prepare(
                "UPDATE uverify
                SET password = :secpass, mktoken = :ekey, mkkey = :eiv,
                mkhash = :enck, recovery_phrase = :rcp
                WHERE iduv = :iduv AND email = :email AND password = :cpass
                AND recovery_phrase = :rcphr"
            );
            $stmt1->execute([
                ':secpass' => $secpass,
                ':ekey'    => $ekey,
                ':eiv'     => $eiv,
                ':enck'    => $enck,
                ':rcp'     => $rcp,
                ':iduv'    => $this->iduv,
                ':email'   => $email,
                ':cpass'   => $cpass,
                ':rcphr'   => $rcphr,
            ]);
            $inst1 = $stmt1->rowCount();

            $stmt2 = $this->conn->prepare(
                "UPDATE users
                SET username = :nname, email = :cml, password = :secpass
                WHERE idUser = :iduv AND mkpin = :pin"
            );
            $stmt2->execute([
                ':nname'   => $nname,
                ':cml'     => $cml,
                ':secpass' => $secpass,
                ':iduv'    => $duv,
                ':pin'     => $pin,
            ]);
            $inst2 = $stmt2->rowCount();

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
                $this->sPMailer($nname, $cml);
            } else {
                $this->conn->rollBack();
                $_SESSION["ErrorMessage"] = "Error in updated the new password.";
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("ChangePass Error: " . $e->getMessage());
            $_SESSION["ErrorMessage"] = "Database error. Please try again.";
        }
    }

    private function sPMailer(string $username, string $email): void
    {
        $usrnm = $this->gc->ende_crypter("decrypt", $username, SECURE_TOKEN, SECURE_HASH);
        $usrml = $this->gc->ende_crypter("decrypt", $email, SECURE_TOKEN, SECURE_HASH);
        $usr   = $this->gc->ende_crypter("decrypt", USEREMAIL, SECURE_TOKEN, SECURE_HASH);
        $pas   = $this->gc->ende_crypter("decrypt", PASSMAIL, SECURE_TOKEN, SECURE_HASH);

        $subject = "Your password has been changed.";
        $body    = "<p>Hello <b>{$usrnm}</b>.</p>";
        $body   .= "<p>Your password has been successfully updated.</p>";
        $body   .= "<p>We recommend saving it in a safe place.</p>";
        $body   .= "<p>Remember to always keep your passwords secure.</p>";

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
                echo "<h4>The email was sent. Your password has been updated.</h4>";
            } else {
                echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
            }
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            echo "<h4>Mailer Error. Please contact support.</h4>";
        }
    }
}
