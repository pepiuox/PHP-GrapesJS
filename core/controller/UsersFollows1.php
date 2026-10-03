<?php
declare(strict_types=1);

/**
 * Gestión de seguidores de usuarios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES CRÍTICAS:
 * - Bug: $this->fucode → $this->fcode (propiedad correcta)
 * - Bug: $this->date->fotmat() → $this->date->format()
 * - SQL: DELETE users_followers WHERE → DELETE FROM users_followers WHERE
 * - CSRF protection añadida
 * - Validación de sesiones
 */
class UsersFollows
{
    private PDO $conn;
    private DateTime $date;
    private string $tble = 'users_followers';
    private string $timestamp;
    private string $ucode;
    private string $fcode;

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
        $this->fcode = $_SESSION['ccode'];

        // ✅ Validar formato alfanumérico
        if (!ctype_alnum($this->ucode) || !ctype_alnum($this->fcode)) {
            throw new RuntimeException('Códigos de usuario inválidos');
        }

        // ✅ BUG CORREGIDO: fotmat → format
        $this->timestamp = $this->date->format('Y-m-d H:i:s');

        if (isset($_POST['follow'])) {
            $this->CheckFollower();
        }

        if (isset($_POST['unfollow'])) {
            $this->unFollowUsr();
        }
    }

    /**
     * Verifica si ya existe un follow y lo crea si no existe.
     *
     * ✅ BUG CORREGIDO: $this->fucode → $this->fcode
     */
    private function CheckFollower(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        // ✅ ANTES: usaba $this->fucode (no existe)
        // ✅ AHORA: usa $this->fcode (propiedad correcta)
        $query = "SELECT usercode, fusercode
        FROM {$this->tble}
        WHERE usercode = :uc AND fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':fc' => $this->fcode]);

        if ($stmt->rowCount() === 0) {
            $this->FollowUsr();
        }
    }

    /**
     * Crea un nuevo follow.
     *
     * ✅ BUG CORREGIDO: $this->fucode → $this->fcode
     */
    private function FollowUsr(): void
    {
        $query = "INSERT INTO {$this->tble} (usercode, fusercode, timestamp)
        VALUES (:uc, :fc, :ts)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':uc' => $this->ucode,
            ':fc' => $this->fcode,
            ':ts' => $this->timestamp
        ]);
    }

    /**
     * Elimina un follow.
     *
     * ✅ BUG CORREGIDO: DELETE FROM (antes faltaba FROM)
     * ✅ BUG CORREGIDO: $this->fucode → $this->fcode
     */
    private function unFollowUsr(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        // ✅ ANTES: "DELETE users_followers WHERE" (faltaba FROM)
        // ✅ AHORA: "DELETE FROM users_followers WHERE"
        $query = "DELETE FROM {$this->tble}
        WHERE usercode = :uc AND fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':fc' => $this->fcode]);
    }

    /**
     * Obtiene el total de seguidores de un usuario.
     */
    public function getTotalFollowers(string $usercode): int
    {
        $query = "SELECT COUNT(*) FROM {$this->tble} WHERE fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':fc' => $usercode]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Obtiene el total de usuarios que sigue el usuario actual.
     */
    public function getTotalFollowing(): int
    {
        $query = "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Verifica si el usuario actual sigue a otro usuario.
     */
    public function isFollowing(string $targetUsercode): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->tble}
        WHERE usercode = :uc AND fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':fc' => $targetUsercode]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
