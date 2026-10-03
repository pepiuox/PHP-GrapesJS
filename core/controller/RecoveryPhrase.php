<?php
declare(strict_types=1);

/**
 * Gestión de frases de recuperación de usuarios.
 * Migrado a PDO con seguridad mejorada.
 *
 * CORRECCIONES:
 * - CSRF protection añadida
 * - exit después de header()
 * - hash_equals para comparación segura
 * - Validación de longitud de frase de recuperación
 * - Inyección de dependencias PDO
 * - Tipado estricto
 */
class RecoveryPhrase
{
    private PDO $conn;
    private string $baseurl;
    private int $iduv;
    private string $usercode;
    private string $mkhash;
    private GetCodeDeEncrypt $gc;
    private Protect $pt;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->baseurl = defined('SITE_PATH') ? SITE_PATH : '/';
        $this->gc = new GetCodeDeEncrypt();
        $this->pt = new Protect($connection);

        // ✅ Validación estricta de sesión
        if (!isset($_SESSION['user_id'], $_SESSION['access_id'], $_SESSION['hash'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        $this->iduv = (int) $_SESSION['user_id'];
        $this->usercode = (string) $_SESSION['access_id'];
        $this->mkhash = (string) $_SESSION['hash'];

        // ✅ Validar formato
        if ($this->iduv <= 0 || !ctype_alnum($this->usercode) || !ctype_alnum($this->mkhash)) {
            throw new RuntimeException('Datos de sesión inválidos');
        }

        if (isset($_POST['makerecoveryphrase'])) {
            $this->MakeRecoveryPhrase();
        }
        if (isset($_POST['updaterecoveryphrase'])) {
            $this->UpdateRecoveryPhrase();
        }
    }

    /**
     * Valida que la frase de recuperación tenga longitud adecuada.
     */
    private function isValidPhraseLength(string $phrase): bool
    {
        $length = mb_strlen($phrase, 'UTF-8');
        return $length >= 10 && $length <= 255;
    }

    /**
     * Crea una nueva frase de recuperación.
     */
    private function MakeRecoveryPhrase(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (empty($_POST['pin']) || empty($_POST['rvphrase'])) {
            $_SESSION['ErrorMessage'] = 'Complete todos los campos requeridos';
            return;
        }

        $rcvphrase = $this->pt->secureStr($_POST['rvphrase']);
        $userpin = $this->pt->secureStr($_POST['pin']);

        // ✅ Validar longitud de frase
        if (!$this->isValidPhraseLength($rcvphrase)) {
            $_SESSION['ErrorMessage'] = 'La frase de recuperación debe tener entre 10 y 255 caracteres';
            return;
        }

        // Validar PIN
        if (!is_numeric($userpin) || strlen($userpin) !== 6) {
            $_SESSION['ErrorMessage'] = 'El PIN debe ser numérico y de 6 dígitos';
            return;
        }

        $cnull = 0;
        $upin = $this->gc->ende_crypter('encrypt', $userpin, SECURE_TOKEN, SECURE_HASH);

        $query = "SELECT mktoken, mkkey, recovery_phrase
        FROM uverify
        WHERE iduv = :id AND usercode = :uc AND mkhash = :mh AND mkpin = :mp AND rp_active = :rp";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':id' => $this->iduv,
            ':uc' => $this->usercode,
            ':mh' => $this->mkhash,
            ':mp' => $upin,
            ':rp' => $cnull
        ]);

        if ($stmt->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'Error en la verificación de datos';
            return;
        }

        $urw = $stmt->fetch(PDO::FETCH_ASSOC);
        $secret_key = $urw['mktoken'];
        $secret_iv = $urw['mkkey'];
        $prhase = $urw['recovery_phrase'];

        $dcrvp = $this->gc->ende_crypter('decrypt', $prhase, $secret_key, $secret_iv);

        // ✅ Comparación segura con hash_equals
        if (!hash_equals($dcrvp, $rcvphrase)) {
            $_SESSION['ErrorMessage'] = 'La frase de recuperación no coincide';
            return;
        }

        $crvp = $this->gc->ende_crypter('encrypt', $rcvphrase, $secret_key, $secret_iv);
        $rpac = 1;

        $update = $this->conn->prepare(
            "UPDATE uverify
            SET recovery_phrase = :rp, rp_active = :rpac
            WHERE iduv = :id AND usercode = :uc AND mkhash = :mh AND mkpin = :mp"
        );

        $result = $update->execute([
            ':rp' => $crvp,
            ':rpac' => $rpac,
            ':id' => $this->iduv,
            ':uc' => $this->usercode,
            ':mh' => $this->mkhash,
            ':mp' => $upin
        ]);

        if ($result && $update->rowCount() === 1) {
            unset($_SESSION['AlertMessage']);
            unset($_SESSION['RecoveryMessage']);
            $_SESSION['SuccessMessage'] = 'Gracias, su cuenta ahora es más segura';
            echo '<script>window.location.href = "profile.php";</script>';
            exit; // ✅ exit agregado
        } else {
            $_SESSION['ErrorMessage'] = 'Error al actualizar los datos';
        }
    }

    /**
     * Actualiza la frase de recuperación existente.
     */
    private function UpdateRecoveryPhrase(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (empty($_POST['pin']) || empty($_POST['rvphrase']) || empty($_POST['urvphrase'])) {
            $_SESSION['ErrorMessage'] = 'Complete todos los campos requeridos';
            return;
        }

        $rcvphrase = $this->pt->secureStr($_POST['rvphrase']);
        $urvphrase = $this->pt->secureStr($_POST['urvphrase']);
        $userpin = $this->pt->secureStr($_POST['pin']);

        // ✅ Validar longitud de frases
        if (!$this->isValidPhraseLength($rcvphrase)) {
            $_SESSION['ErrorMessage'] = 'La frase de recuperación actual debe tener entre 10 y 255 caracteres';
            return;
        }

        if (!$this->isValidPhraseLength($urvphrase)) {
            $_SESSION['ErrorMessage'] = 'La nueva frase de recuperación debe tener entre 10 y 255 caracteres';
            return;
        }

        // Validar PIN
        if (!is_numeric($userpin) || strlen($userpin) !== 6) {
            $_SESSION['ErrorMessage'] = 'El PIN debe ser numérico y de 6 dígitos';
            return;
        }

        $cnull = 1;
        $upin = $this->gc->ende_crypter('encrypt', $userpin, SECURE_TOKEN, SECURE_HASH);

        $query = "SELECT mktoken, mkkey, recovery_phrase
        FROM uverify
        WHERE iduv = :id AND usercode = :uc AND mkhash = :mh AND mkpin = :mp AND rp_active = :rp";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':id' => $this->iduv,
            ':uc' => $this->usercode,
            ':mh' => $this->mkhash,
            ':mp' => $upin,
            ':rp' => $cnull
        ]);

        if ($stmt->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'Error en la verificación de datos';
            return;
        }

        $urw = $stmt->fetch(PDO::FETCH_ASSOC);
        $secret_key = $urw['mktoken'];
        $secret_iv = $urw['mkkey'];
        $prhase = $urw['recovery_phrase'];

        $dcrvp = $this->gc->ende_crypter('decrypt', $prhase, $secret_key, $secret_iv);

        // ✅ Comparación segura con hash_equals
        if (!hash_equals($dcrvp, $rcvphrase)) {
            $_SESSION['ErrorMessage'] = 'La frase de recuperación actual no coincide';
            return;
        }

        $crvp = $this->gc->ende_crypter('encrypt', $urvphrase, $secret_key, $secret_iv);

        $update = $this->conn->prepare(
            "UPDATE uverify
            SET recovery_phrase = :rp
            WHERE iduv = :id AND usercode = :uc AND mkhash = :mh AND mkpin = :mp"
        );

        $result = $update->execute([
            ':rp' => $crvp,
            ':id' => $this->iduv,
            ':uc' => $this->usercode,
            ':mh' => $this->mkhash,
            ':mp' => $upin
        ]);

        if ($result && $update->rowCount() === 1) {
            unset($_SESSION['AlertMessage']);
            unset($_SESSION['RecoveryMessage']);
            $_SESSION['SuccessMessage'] = 'Gracias, su cuenta ahora es más segura';
            echo '<script>window.location.href = "profile.php";</script>';
            exit; // ✅ exit agregado
        } else {
            $_SESSION['ErrorMessage'] = 'Error al actualizar los datos';
        }
    }
}
