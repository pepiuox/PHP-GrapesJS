<?php
declare(strict_types=1);

/**
 * Consulta de notas de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público (antes era privado sin invocar)
 * - Validación de sesión
 * - Inyección de dependencias PDO
 * - Tipado estricto
 */
class UsersNotes
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_notes';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;

        // ✅ Validación estricta de sesión
        if (!isset($_SESSION['access_id']) || !is_string($_SESSION['access_id'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        $this->ucode = $_SESSION['access_id'];

        // ✅ Validar formato alfanumérico
        if (!ctype_alnum($this->ucode)) {
            throw new RuntimeException('Código de usuario inválido');
        }
    }

    /**
     * Obtiene las notas del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uNotes(): ?array
    {
        $sql = "SELECT * FROM {$this->tble} WHERE usercode = :uc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uc' => $this->ucode]);

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    /**
     * Crea o actualiza una nota del usuario.
     */
    public function saveNote(string $title, string $content): bool
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return false;
        }

        $existing = $this->uNotes();

        if ($existing !== null) {
            $sql = "UPDATE {$this->tble}
            SET title = :title, content = :content, updated_at = NOW()
            WHERE usercode = :uc";
        } else {
            $sql = "INSERT INTO {$this->tble} (usercode, title, content, created_at)
            VALUES (:uc, :title, :content, NOW())";
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':uc'      => $this->ucode,
            ':title'   => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
                              ':content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
        ]);
    }

    /**
     * Elimina las notas del usuario.
     */
    public function deleteNotes(): bool
    {
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            return false;
        }

        $sql = "DELETE FROM {$this->tble} WHERE usercode = :uc";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':uc' => $this->ucode]);
    }
}
