<?php
declare(strict_types=1);

/**
 * Gestión de likes de usuarios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES CRÍTICAS:
 * - Bug: $this->fucode → $this->lcode (propiedad correcta)
 * - Bug: $this->date->fotmat() → $this->date->format()
 * - SQL: DELETE users_likes WHERE → DELETE FROM users_likes WHERE
 * - Bug: constructor chequeaba $_POST["follow"] pero método chequeaba $_POST["like"]
 * - Bug: llamaba $this->unlikeUsr() pero el método se llama unLikeUsr()
 * - CSRF protection añadida
 * - Validación de sesiones
 */
class UsersLikes
{
    private PDO $conn;
    private DateTime $date;
    private string $tble = 'users_likes';
    private string $timestamp;
    private string $ucode;
    private string $lcode;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->date = new DateTime();

        // ✅ Validación estricta de sesiones
        if (!isset($_SESSION['access_id']) || !is_string($_SESSION['access_id'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        if (!isset($_SESSION['ccode']) || !is_string($_SESSION['ccode'])) {
            throw new RuntimeException('Código de contenido no válido');
        }

        $this->ucode = $_SESSION['access_id'];
        $this->lcode = $_SESSION['ccode'];

        // ✅ Validar formato alfanumérico
        if (!ctype_alnum($this->ucode) || !ctype_alnum($this->lcode)) {
            throw new RuntimeException('Códigos de usuario inválidos');
        }

        // ✅ BUG CORREGIDO: fotmat → format
        $this->timestamp = $this->date->format('Y-m-d H:i:s');

        // ✅ BUG CORREGIDO: ahora chequea $_POST["like"] consistentemente
        if (isset($_POST['like'])) {
            $this->CheckLike();
        }

        // ✅ BUG CORREGIDO: nombre de método consistente
        if (isset($_POST['unlike'])) {
            $this->unLikeUsr();
        }
    }

    /**
     * Verifica si ya existe un like y lo crea si no existe.
     */
    private function CheckLike(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        // ✅ BUG CORREGIDO: $this->fucode → $this->lcode
        $query = "SELECT usercode, lusercode
        FROM {$this->tble}
        WHERE usercode = :uc AND lusercode = :lc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':lc' => $this->lcode]);

        if ($stmt->rowCount() === 0) {
            $this->LikeUsr();
        }
    }

    /**
     * Crea un nuevo like.
     */
    private function LikeUsr(): void
    {
        $query = "INSERT INTO {$this->tble} (usercode, lusercode, timestamp)
        VALUES (:uc, :lc, :ts)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':uc' => $this->ucode,
            ':lc' => $this->lcode,
            ':ts' => $this->timestamp
        ]);
    }

    /**
     * Elimina un like.
     *
     * ✅ BUG CORREGIDO: DELETE FROM (antes faltaba FROM)
     * ✅ BUG CORREGIDO: $this->fucode → $this->lcode
     */
    private function unLikeUsr(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        // ✅ ANTES: "DELETE users_likes WHERE" (faltaba FROM)
        // ✅ AHORA: "DELETE FROM users_likes WHERE"
        $query = "DELETE FROM {$this->tble}
        WHERE usercode = :uc AND lusercode = :lc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':lc' => $this->lcode]);
    }

    /**
     * Obtiene el total de likes de un usuario.
     */
    public function getTotalLikes(string $usercode): int
    {
        $query = "SELECT COUNT(*) FROM {$this->tble} WHERE lusercode = :lc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':lc' => $usercode]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Verifica si el usuario actual ha dado like.
     */
    public function hasLiked(string $targetUsercode): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->tble}
        WHERE usercode = :uc AND lusercode = :lc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':lc' => $targetUsercode]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
