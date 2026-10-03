<?php
declare(strict_types=1);

/**
 * Verificación alternativa de usuarios.
 * - Migrado a PDO.
 * - Corregido bug del mensaje de éxito.
 * - Corregida sintaxis SQL.
 * - Sanitización de GET antes de guardarlo en sesión.
 */
class UserVerify
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

        if (isset($_GET['id'], $_GET['code'], $_GET['hash'])) {
            $id   = (int) $_GET['id'];
            $code = ctype_alnum($_GET['code']) ? $_GET['code'] : '';
            $hash = ctype_alnum($_GET['hash']) ? $_GET['hash'] : '';

            if ($id > 0 && $code !== '' && $hash !== '') {
                $_SESSION['id']   = $id;
                $_SESSION['code'] = $code;
                $_SESSION['hash'] = $hash;
            }
        }

        if (isset($_POST['bverify'])) {
            $this->verify();
        }
    }

    private function generateKey(int $len = 64): string
    {
        // ✅ Reemplazamos sha1 por hash('sha256', ...)
        return substr(hash('sha256', random_bytes(32)), 0, $len);
    }

    private function verify(): void
    {
        if (empty($_POST['id']) || empty($_POST['code']) || empty($_POST['hash'])) {
            $_SESSION['ErrorMessage'] = 'Faltan datos para verificar la cuenta.';
            return;
        }

        $id   = (int) ($_SESSION['id']   ?? 0);
        $code = (string) ($_SESSION['code'] ?? '');
        $hash = (string) ($_SESSION['hash'] ?? '');

        $userEmail = filter_var($_POST['id'], FILTER_SANITIZE_EMAIL);
        $actCode   = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['code']);
        $hashCode  = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['hash']);

        // Comparación segura
        if (!hash_equals($code, $actCode) || !hash_equals($hash, $hashCode)) {
            $_SESSION['ErrorMessage'] = 'Código o hash inválidos.';
            return;
        }

        $stmt = $this->connection->prepare(
            "SELECT iduv FROM uverify
            WHERE email = :e AND mkhash = :h AND activation_code = :c"
        );
        $stmt->execute([':e' => $userEmail, ':h' => $hashCode, ':c' => $actCode]);

        if ($stmt->rowCount() === 0) {
            $_SESSION['ErrorMessage'] = 'No se encontró el registro de verificación.';
            return;
        }
        $urw = $stmt->fetch(PDO::FETCH_ASSOC);
        $uid = (int) $urw['iduv'];

        $mhash    = $this->generateKey();
        $verified = 1;
        $bann     = 0;
        $status   = 1;

        $this->connection->beginTransaction();
        try {
            $s1 = $this->connection->prepare(
                "UPDATE uverify
                SET mkhash = :mh, activation_code = NULL, is_activate = :ia, banned = :b
                WHERE iduv = :id"
            );
            $s1->execute([':mh'=>$mhash, ':ia'=>$verified, ':b'=>$bann, ':id'=>$uid]);

            $s2 = $this->connection->prepare(
                "UPDATE users
                SET verified = :v, status = :st, email_verified = NULL
                WHERE idUser = :id"
            );
            $s2->execute([':v'=>$verified, ':st'=>$status, ':id'=>$uid]);

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log('UserVerify error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error interno al activar la cuenta.';
            return;
        }

        // ✅ BUG CORREGIDO: antes decía "Error in verifying..." en el SuccessMessage
        $_SESSION['SuccessMessage'] = '¡Cuenta verificada y activada correctamente!';
        header('Location: login.php');
        exit;
    }
}
