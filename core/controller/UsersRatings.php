<?php
declare(strict_types=1);

/**
 * Gestión de ratings/likes de usuarios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES:
 * - Bug: $this->fucode → $this->fcode
 * - Bug: $this->date->fotmat() → $this->date->format()
 * - SQL DELETE inválido: falta FROM
 * - Método checkRatings() ahora es público
 * - Validación de sesiones
 * - CSRF protection
 */
class UsersRatings
{
    private PDO $conn;
    private string $ucode;
    private string $fcode;
    private string $timestamp;
    private string $tble = 'users_likes';
    private DateTime $date;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->date = new DateTime();

        // Validación estricta de sesiones
        if (!isset($_SESSION['ucode']) || !is_string($_SESSION['ucode'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        if (!isset($_SESSION['ccode']) || !is_string($_SESSION['ccode'])) {
            throw new RuntimeException('Código de contenido no válido');
        }

        $this->ucode = $_SESSION['ucode'];
        $this->fcode = $_SESSION['ccode'];

        // Validar formato alfanumérico
        if (!ctype_alnum($this->ucode) || !ctype_alnum($this->fcode)) {
            throw new RuntimeException('Códigos de usuario inválidos');
        }

        $this->timestamp = $this->date->format('Y-m-d H:i:s');

        if (isset($_POST["like"])) {
            $this->checkRatings();
        }

        if (isset($_POST["unfollow"])) {
            $this->unRatingUsr();
        }
    }

    /**
     * Verifica si ya existe un rating y lo crea si no existe.
     */
    public function checkRatings(): void
    {
        if (!isset($_POST["like"])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        $query = "SELECT usercode, fusercode FROM {$this->tble}
        WHERE usercode = :uc AND fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':fc' => $this->fcode]);

        if ($stmt->rowCount() === 0) {
            $this->ratingUsr();
        }
    }

    /**
     * Crea un nuevo rating/like.
     */
    private function ratingUsr(): void
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
     * Elimina un rating/like.
     *
     * ✅ CORREGIDO: SQL ahora usa DELETE FROM correctamente
     */
    public function unRatingUsr(): void
    {
        if (!isset($_POST["unfollow"])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        // ✅ BUG CORREGIDO: ahora usa DELETE FROM correctamente
        $query = "DELETE FROM {$this->tble}
        WHERE usercode = :uc AND fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':fc' => $this->fcode]);
    }

    /**
     * Obtiene el número total de ratings de un usuario.
     */
    public function getTotalRatings(string $usercode): int
    {
        $query = "SELECT COUNT(*) FROM {$this->tble} WHERE fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':fc' => $usercode]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Verifica si el usuario actual ha dado like a otro usuario.
     */
    public function hasRated(string $targetUsercode): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->tble}
        WHERE usercode = :uc AND fusercode = :fc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uc' => $this->ucode, ':fc' => $targetUsercode]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
