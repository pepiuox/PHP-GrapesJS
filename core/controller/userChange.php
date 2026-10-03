<?php
declare(strict_types=1);

/**
 * Cambio de contraseña y PIN de usuarios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES CRÍTICAS:
 * - SQL inválido: mkhash=, (falta valor)
 * - Variable mal escrita: $$enck
 * - SQL inválido: SELECT idUser, FROM (coma extra)
 * - SQL inválido: UPDATE uverify mkpin= (falta SET)
 * - Inyección SQL directa en updatePIN()
 * - Funciones movidas fuera de métodos
 * - CSRF protection añadida
 * - hash_equals para comparación segura
 */
class UserChange
{
    private PDO $connection;
    private string $baseurl;

    public function __construct(PDO $conn)
    {
        $this->connection = $conn;
        $this->baseurl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname($_SERVER['PHP_SELF'] ?? '/');

        if (isset($_POST["changePassword"])) {
            $this->updatePassword();
        }
        if (isset($_POST["changePIN"])) {
            $this->updatePIN();
        }
    }

    /**
     * Actualiza la contraseña del usuario.
     */
    private function updatePassword(): void
    {
        if (!isset($_POST['updatePassword'])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (!isset($_GET['email'], $_GET['key'], $_GET['hash'])) {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        // Sanitizar parámetros GET
        $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
        $changekey = ctype_alnum($_GET['key']) ? $_GET['key'] : '';
        $hash = ctype_alnum($_GET['hash']) ? $_GET['hash'] : '';

        if ($email === '' || $changekey === '' || $hash === '') {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        // Verificar token de recuperación
        $chck = $this->connection->prepare(
            "SELECT * FROM change_pass WHERE email = :e AND mkhash = :h AND password_key = :k"
        );
        $chck->execute([':e' => $email, ':h' => $hash, ':k' => $changekey]);
        $ct = $chck->fetch(PDO::FETCH_ASSOC);

        if (!$ct) {
            $_SESSION['ErrorMessage'] = 'Token de recuperación inválido';
            return;
        }

        $nowTime = date("Y-m-d H:i:s");
        if ($nowTime >= $ct['expire']) {
            $_SESSION['ErrorMessage'] = 'Tiempo expirado para restablecer la contraseña';
            header('Location: index.php');
            exit;
        }

        // Validar campos del formulario
        if (empty($_POST['vemail']) || empty($_POST['recoveryphrase']) ||
            empty($_POST['password2']) || empty($_POST['password3'])) {
            $_SESSION['ErrorMessage'] = 'Por favor complete todos los campos requeridos';
        return;
            }

            $vemail = trim($_POST['vemail']);
            $recoveryphrase = trim($_POST['recoveryphrase']);
            $password2 = trim($_POST['password2']);
            $password3 = trim($_POST['password3']);

            // ✅ Comparación segura con hash_equals
            if (!hash_equals($vemail, $email)) {
                $_SESSION['ErrorMessage'] = 'Los datos no coinciden para actualizar su contraseña';
                return;
            }

            if ($password3 !== $password2) {
                $_SESSION['ErrorMessage'] = 'Las contraseñas no coinciden';
                return;
            }

            // Validar longitud mínima de contraseña
            if (strlen($password2) < 8) {
                $_SESSION['ErrorMessage'] = 'La contraseña debe tener al menos 8 caracteres';
                return;
            }

            // Verificar frase de recuperación
            $stmt = $this->connection->prepare(
                "SELECT * FROM uverify WHERE email = :e AND mkhash = :h AND password_key = :k AND recovery_phrase = :r"
            );
            $stmt->execute([':e' => $email, ':h' => $hash, ':k' => $changekey, ':r' => $recoveryphrase]);

            if ($stmt->rowCount() !== 1) {
                $_SESSION['ErrorMessage'] = 'Los datos no coinciden para actualizar su contraseña';
                return;
            }

            $dt = $stmt->fetch(PDO::FETCH_ASSOC);
            $duv = (int) $dt['iduv'];
            $pin = $dt['mkpin'];

            // ✅ Generar claves de encriptación
            $ekey = self::randHash();
            $eiv = self::randKey();
            $enck = self::encKey();

            // Encriptar contraseña y email
            $securing = self::endeCrypter('encrypt', $password2, $ekey, $eiv);
            $cml = self::endeCrypter('encrypt', $email, $ekey, $eiv);
            $clenkey = '';

            // ✅ TRANSACCIÓN para garantizar atomicidad
            $this->connection->beginTransaction();
            try {
                // ✅ BUG CORREGIDO: ahora tiene todos los valores correctamente
                $upd = $this->connection->prepare(
                    "UPDATE uverify SET
                    password = :pass,
                    mktoken = :mt,
                    mkkey = :mk,
                    mkhash = :mh,
                    password_key = :pk
                    WHERE email = :e AND recovery_phrase = :r AND password_key = :k"
                );
                $upd->execute([
                    ':pass' => $securing,
                    ':mt' => $ekey,
                    ':mk' => $eiv,
                    ':mh' => $enck,
                    ':pk' => $clenkey,
                    ':e' => $email,
                    ':r' => $recoveryphrase,
                    ':k' => $changekey
                ]);

                if ($upd->rowCount() !== 1) {
                    throw new Exception('No se pudo actualizar uverify');
                }

                $stmt2 = $this->connection->prepare(
                    "UPDATE users SET email = :e, password = :p WHERE idUser = :id AND mkpin = :pin"
                );
                $stmt2->execute([
                    ':e' => $cml,
                    ':p' => $securing,
                    ':id' => $duv,
                    ':pin' => $pin
                ]);

                if ($stmt2->rowCount() !== 1) {
                    throw new Exception('No se pudo actualizar users');
                }

                $this->connection->commit();

                header('Location: index.php');
                exit;

            } catch (Exception $e) {
                $this->connection->rollBack();
                error_log('UserChange updatePassword error: ' . $e->getMessage());
                $_SESSION['ErrorMessage'] = 'Error al actualizar la contraseña';
            }
    }

    /**
     * Actualiza el PIN del usuario.
     *
     * ✅ CORREGIDO: Inyección SQL eliminada, ahora usa prepared statements
     */
    private function updatePIN(): void
    {
        if (!isset($_POST['updatePIN'])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (!isset($_GET['email'], $_GET['key'], $_GET['hash'])) {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        // Sanitizar parámetros GET
        $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
        $changekey = ctype_alnum($_GET['key']) ? $_GET['key'] : '';
        $hash = ctype_alnum($_GET['hash']) ? $_GET['hash'] : '';

        if ($email === '' || $changekey === '' || $hash === '') {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        // Verificar token de recuperación
        $chck = $this->connection->prepare(
            "SELECT * FROM change_pin WHERE email = :e AND mkhash = :h AND password_key = :k"
        );
        $chck->execute([':e' => $email, ':h' => $hash, ':k' => $changekey]);
        $ct = $chck->fetch(PDO::FETCH_ASSOC);

        if (!$ct) {
            $_SESSION['ErrorMessage'] = 'Token de recuperación inválido';
            return;
        }

        $nowTime = date("Y-m-d H:i:s");
        if ($nowTime >= $ct['expire']) {
            $_SESSION['ErrorMessage'] = 'Tiempo expirado para restablecer el PIN';
            header('Location: index.php');
            exit;
        }

        // Validar campos del formulario
        if (empty($_POST['vemail']) || empty($_POST['recoveryphrase'])) {
            $_SESSION['ErrorMessage'] = 'Por favor complete todos los campos requeridos';
            return;
        }

        $vemail = trim($_POST['vemail']);
        $recoveryphrase = trim($_POST['recoveryphrase']);

        // ✅ Comparación segura con hash_equals
        if (!hash_equals($vemail, $email)) {
            $_SESSION['ErrorMessage'] = 'Los datos no coinciden para actualizar su PIN';
            return;
        }

        // ✅ CORREGIDO: Ahora usa prepared statements en lugar de inyección SQL
        $very = $this->connection->prepare(
            "SELECT * FROM uverify WHERE email = :e AND pin_key = :k AND recovery_phrase = :r"
        );
        $very->execute([':e' => $email, ':k' => $changekey, ':r' => $recoveryphrase]);

        if ($very->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'Los datos no coinciden para actualizar su PIN';
            return;
        }

        $dt = $very->fetch(PDO::FETCH_ASSOC);
        $duv = (int) $dt['iduv'];

        // ✅ CORREGIDO: Ahora usa prepared statements
        $fnal = $this->connection->prepare(
            "SELECT idUser FROM users WHERE email = :e"
        );
        $checkm = self::endeCrypter('encrypt', $email, $dt['mktoken'], $dt['mkkey']);
        $fnal->execute([':e' => $checkm]);

        if ($fnal->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'Usuario no encontrado';
            return;
        }

        $rt = $fnal->fetch(PDO::FETCH_ASSOC);

        if ($duv !== (int) $rt['idUser']) {
            $_SESSION['ErrorMessage'] = 'Datos de usuario inconsistentes';
            return;
        }

        // Generar PIN de 6 dígitos
        $cpin = random_int(0, 999999);
        $npin = str_pad((string) $cpin, 6, '0', STR_PAD_LEFT);
        $clenkey = '';

        // ✅ TRANSACCIÓN para garantizar atomicidad
        $this->connection->beginTransaction();
        try {
            // ✅ BUG CORREGIDO: ahora usa SET correctamente
            $upd = $this->connection->prepare(
                "UPDATE uverify SET mkpin = :pin, pin_key = :pk
                WHERE email = :e AND recovery_phrase = :r"
            );
            $upd->execute([
                ':pin' => $npin,
                ':pk' => $clenkey,
                ':e' => $email,
                ':r' => $recoveryphrase
            ]);

            if ($upd->rowCount() !== 1) {
                throw new Exception('No se pudo actualizar uverify');
            }

            $stmt2 = $this->connection->prepare(
                "UPDATE users SET mkpin = :pin WHERE idUser = :id AND mkpin = :oldpin"
            );
            $stmt2->execute([
                ':pin' => $npin,
                ':id' => $duv,
                ':oldpin' => $dt['mkpin']
            ]);

            if ($stmt2->rowCount() !== 1) {
                throw new Exception('No se pudo actualizar users');
            }

            $this->connection->commit();

            header('Location: index.php');
            exit;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log('UserChange updatePIN error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error al actualizar el PIN';
        }
    }

    /**
     * ✅ Funciones movidas fuera de métodos y mejoradas
     */
    private static function randHash(int $len = 32): string
    {
        return substr(bin2hex(random_bytes(32)), 0, $len);
    }

    private static function randKey(int $len = 32): string
    {
        return substr(bin2hex(random_bytes(32)), 0, $len);
    }

    private static function encKey(int $len = 32): string
    {
        return substr(bin2hex(random_bytes(32)), 0, $len);
    }

    private static function endeCrypter(string $action, string $string, string $secret_key, string $secret_iv): string
    {
        $encrypt_method = 'AES-256-CBC';
        $key = hash('sha256', $secret_key, true);
        $iv = substr(hash('sha256', $secret_iv, true), 0, 16);

        if ($action === 'encrypt') {
            return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else {
            return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
}
