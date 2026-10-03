<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Verificación de usuarios con PHPMailer.
 * Migrado a PDO, con transacciones y comparación segura de tokens.
 */
class UsersVerify
{
    private PDO $conn;
    private UsersCodeAccess $uca;
    private GetCodeDeEncrypt $gc;
    private Protect $pt;
    private string $code = '';
    private string $hash = '';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->uca  = new UsersCodeAccess();
        $this->gc   = new GetCodeDeEncrypt();
        $this->pt   = new Protect();

        $this->includes();

        if (isset($_GET['vcode'], $_GET['usr'])) {
            // Guardamos en sesión solo tras validar formato
            $vcode = $this->pt->secureStr($_GET['vcode']);
            $usr   = $this->pt->secureStr($_GET['usr']);
            if (ctype_alnum($vcode) && ctype_alnum($usr)) {
                $_SESSION['vcode'] = $this->code = $vcode;
                $_SESSION['urs']   = $this->hash = $usr;
            }
        }

        if (isset($_POST['bverify'])) {
            $this->uVerify();
        }
    }

    private function includes(): void
    {
        require_once __DIR__ . "/../PHPMailer/src/Exception.php";
        require_once __DIR__ . "/../PHPMailer/src/PHPMailer.php";
        require_once __DIR__ . "/../PHPMailer/src/SMTP.php";
    }

    /**
     * Verifica email + código usando comparación segura contra timing-attacks.
     */
    private function uVerify(): void
    {
        if (empty($_POST['code']) || empty($_POST['hash'])) {
            $_SESSION['ErrorMessage'] = 'Datos de verificación incompletos.';
            return;
        }

        $actCode  = $this->pt->secureStr($_POST['code']);
        $hashPost = $this->pt->secureStr($_POST['hash']);

        // Comparación segura (timing-safe)
        if (!hash_equals($this->code, $actCode) || !hash_equals($this->hash, $hashPost)) {
            $_SESSION['ErrorMessage'] = 'Código o hash inválidos.';
            return;
        }

        $stmt = $this->conn->prepare(
            "SELECT iduv, usercode, email, username, usr_type
            FROM uverify WHERE mkhash = :h AND activation_code = :c"
        );
        $stmt->execute([':h' => $hashPost, ':c' => $actCode]);

        if ($stmt->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'No se encontró el registro de verificación.';
            return;
        }
        $urw = $stmt->fetch(PDO::FETCH_ASSOC);

        $uid      = (int) $urw['iduv'];
        $uscod    = $urw['usercode'];
        $username = $urw['username'];
        $email    = $urw['email'];
        $usrtype  = $urw['usr_type'];

        $valusr = match ($usrtype) {
            'cliente'    => '3',
            'servicios'  => '5',
            'productor'  => '7',
            default      => '0',
        };

        // Validar acción previa
        $as = $this->conn->prepare(
            "SELECT action FROM users_actions WHERE usercode = :u AND validation = :v"
        );
        $as->execute([':u' => $uscod, ':v' => $actCode]);
        $urac = $as->fetch(PDO::FETCH_ASSOC);

        if (($urac['action'] ?? '') !== 'validation') {
            $_SESSION['ErrorMessage'] = 'Acción de activación no válida.';
            return;
        }

        $mhash    = $this->gc->randHash();
        $cchng    = $this->gc->getIdCode();
        $apr      = $this->gc->getRandKey();
        $ver      = $this->gc->getIdCode();
        $folder   = $this->gc->randLengthString(19);
        $verified = 1;
        $bann     = 0;
        $status   = 1;

        /* ---------- Transacción atómica ---------- */
        $this->conn->beginTransaction();
        try {
            $s1 = $this->conn->prepare(
                "UPDATE uverify SET mkhash=:mh, activation_code=:ac, is_activate=:ia, banned=:b
                WHERE iduv=:id AND usercode=:u AND mkhash=:oh"
            );
            $s1->execute([
                ':mh'=>$mhash, ':ac'=>$cchng, ':ia'=>$verified, ':b'=>$bann,
                ':id'=>$uid, ':u'=>$uscod, ':oh'=>$this->hash
            ]);

            $s2 = $this->conn->prepare(
                "UPDATE users SET verified=:v, status=:st, email_verified=:ev
                WHERE idUser=:id AND usercode=:u"
            );
            $s2->execute([':v'=>$verified, ':st'=>$status, ':ev'=>$cchng, ':id'=>$uid, ':u'=>$uscod]);

            $s3 = $this->conn->prepare(
                "UPDATE users_profiles SET mkhash=:mh WHERE idp=:id AND usercode=:u AND mkhash=:oh"
            );
            $s3->execute([':mh'=>$mhash, ':id'=>$uid, ':u'=>$uscod, ':oh'=>$this->hash]);

            $s4 = $this->conn->prepare(
                "UPDATE users_info SET active=:a WHERE userid=:id AND usercode=:u"
            );
            $s4->execute([':a'=>$verified, ':id'=>$uid, ':u'=>$uscod]);

            $s5 = $this->conn->prepare(
                "UPDATE users_types SET user_type=:t, val_user=:vu WHERE usercode=:u"
            );
            $s5->execute([':t'=>$usrtype, ':vu'=>$valusr, ':u'=>$uscod]);

            $this->uca->UpActions($uscod, $cchng, $ver, $apr);
            $this->uca->UpActive($uscod, $verified);
            $this->uca->UpPlans($uscod, $verified);
            $this->uca->UpPrivacy($uscod, $uid, $ver);
            $this->uca->UpSecures($uscod, $uid, $folder, $cchng);
            $this->uca->UpVerify($uscod, $ver);

            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('UsersVerify transaction failed: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error interno al activar la cuenta.';
            return;
        }

        unset($_SESSION['vcode'], $_SESSION['usr']);
        $this->sendPMailer($email, $username);
        $_SESSION['SuccessMessage'] = '¡Cuenta verificada y activada correctamente!';
        header('Location: login.php');
        exit;
    }

    private function sendPMailer(string $email, string $username): void
    {
        $usrml = $this->gc->ende_crypter("decrypt", $email,    SECURE_TOKEN, SECURE_HASH);
        $usrnm = $this->gc->ende_crypter("decrypt", $username, SECURE_TOKEN, SECURE_HASH);
        $usr   = $this->gc->ende_crypter("decrypt", USEREMAIL, SECURE_TOKEN, SECURE_HASH);
        $pas   = $this->gc->ende_crypter("decrypt", PASSMAIL, SECURE_TOKEN, SECURE_HASH);

        // Escapado para prevenir XSS en el HTML del email
        $safeName  = htmlspecialchars($usrnm, ENT_QUOTES, 'UTF-8');
        $safeEmail = filter_var($usrml, FILTER_VALIDATE_EMAIL) ? $usrml : '';
        if ($safeEmail === '') return;

        $subject = "Su cuenta está verificada y activada.";
        $body    = "<html><body>"
        . "<p><b>Hola {$safeName}.</b></p>"
        . "<p>Tu cuenta está activada.<br>Por favor crea tu frase de recuperación.</p>"
        . "</body></html>";

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
            $mail->addAddress($safeEmail, $safeName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}
