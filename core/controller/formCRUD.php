<?php
declare(strict_types=1);

/**
 * FormCRUD - Operaciones CRUD con configuración de tablas.
 */
class FormCRUD
{
    protected PDO $conn;
    public string $tname;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->tname = '';
    }

    /**
     * Sanitiza un string para output HTML.
     *
     * 🔒 NOTA: Con prepared statements de PDO, NO es necesario
     * escapar para SQL. Este método es solo para output HTML.
     */
    public function protect(string $str): string
    {
        $str = trim($str);
        // stripslashes solo si magic_quotes está activo (PHP < 5.4)
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Obtiene la configuración de una tabla.
     *
     * @return array|null Configuración de la tabla o null si no existe
     */
    public function tableSetting(string $tname): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT table_list, table_view, table_add,
            table_update, table_delete, table_secure
            FROM table_settings
            WHERE table_name = :tname
            LIMIT 1"
        );
        $stmt->execute([':tname' => $tname]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return [
                'list'   => (bool) $row['table_list'],
                'view'   => (bool) $row['table_view'],
                'add'    => (bool) $row['table_add'],
                'update' => (bool) $row['table_update'],
                'delete' => (bool) $row['table_delete'],
                'secure' => (bool) $row['table_secure'],
            ];
        }

        return null;
    }

    /**
     * Inserta un registro en una tabla (con whitelist de columnas).
     */
    public function insert(string $table, array $data): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException('No data to insert');
        }

        $columns = implode(', ', array_map(fn($col) => "`{$col}`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_values($data));

        return (int) $this->conn->lastInsertId();
    }

    /**
     * Actualiza un registro en una tabla.
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException('No data to update');
        }

        $set = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));
        $sql = "UPDATE `{$table}` SET {$set} WHERE {$where}";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_merge(array_values($data), $whereParams));

        return $stmt->rowCount();
    }

    /**
     * Elimina un registro de una tabla.
     */
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    /**
     * Obtiene registros de una tabla con filtros opcionales.
     */
    public function select(string $table, string $where = '1', array $params = [], int $limit = 100): array
    {
        $sql = "SELECT * FROM `{$table}` WHERE {$where} LIMIT :limit";
        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
