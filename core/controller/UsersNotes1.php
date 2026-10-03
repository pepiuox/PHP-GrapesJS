<?php
declare(strict_types=1);

/**
 * Gestión de notas personales de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación estricta de sesión
 * - Sanitización de contenido de notas (XSS)
 * - Inyección de dependencias PDO
 */
class UsersNotes
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_notes';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;

        if (!isset($_SESSION['access_id']) || !is_string($_SESSION['access_id'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        $this->ucode = $_SESSION['access_id'];

        if (!ctype_alnum($this->ucode)) {
            throw new RuntimeException('Código de usuario inválido');
        }
    }

    /**
     * Obtiene todas las notas del usuario.
     *
     * @return array Lista de notas
     */
    public function uNotes(): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->tble} WHERE usercode = :uc ORDER BY created_at DESC"
        );
        $stmt->execute([':uc' => $this->ucode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una nota específica por ID.
     * ✅ Verifica que la nota pertenezca al usuario actual.
     */
    public function getNoteById(int $noteId): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->tble} WHERE id = :id AND usercode = :uc"
        );
        $stmt->execute([':id' => $noteId, ':uc' => $this->ucode]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Crea una nueva nota.
     * ✅ Sanitización XSS del contenido
     */
    public function createNote(string $title, string $content): int|false
    {
        // Sanitizar entrada
        $safeTitle   = htmlspecialchars(strip_tags($title), ENT_QUOTES, 'UTF-8');
        $safeContent = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        if (mb_strlen($safeTitle) === 0 || mb_strlen($safeTitle) > 255) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->tble} (usercode, title, content, created_at, updated_at)
        VALUES (:uc, :t, :c, NOW(), NOW())"
        );
        $stmt->execute([
            ':uc' => $this->ucode,
            ':t'  => $safeTitle,
            ':c'  => $safeContent
        ]);

        return (int) $this->conn->lastInsertId();
    }

    /**
     * Actualiza una nota existente.
     * ✅ Verifica propiedad antes de actualizar
     */
    public function updateNote(int $noteId, string $title, string $content): bool
    {
        // Verificar que la nota existe y pertenece al usuario
        if ($this->getNoteById($noteId) === null) {
            return false;
        }

        $safeTitle   = htmlspecialchars(strip_tags($title), ENT_QUOTES, 'UTF-8');
        $safeContent = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        $stmt = $this->conn->prepare(
            "UPDATE {$this->tble}
            SET title = :t, content = :c, updated_at = NOW()
        WHERE id = :id AND usercode = :uc"
        );
        return $stmt->execute([
            ':t'   => $safeTitle,
            ':c'   => $safeContent,
            ':id'  => $noteId,
            ':uc'  => $this->ucode
        ]);
    }

    /**
     * Elimina una nota.
     * ✅ Verifica propiedad antes de eliminar
     */
    public function deleteNote(int $noteId): bool
    {
        if ($this->getNoteById($noteId) === null) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->tble} WHERE id = :id AND usercode = :uc"
        );
        return $stmt->execute([':id' => $noteId, ':uc' => $this->ucode]);
    }

    /**
     * Obtiene el total de notas del usuario.
     */
    public function countNotes(): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc"
        );
        $stmt->execute([':uc' => $this->ucode]);
        return (int) $stmt->fetchColumn();
    }
}
