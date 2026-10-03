<?php
declare(strict_types=1);

/**
 * Ejecutor de búsquedas globales en la base de datos.
 *
 * ⚠️ SEGURIDAD: Solo busca en tablas permitidas (whitelist).
 * Previene SQL injection y exposición de tablas del sistema.
 */
class Ejecutor
{
    protected PDO $conn;

    /**
     * Lista blanca de tablas permitidas para búsqueda.
     * Agrega aquí las tablas que deben ser buscables.
     */
    private array $allowedTables = [
        'users', 'uverify', 'blog_posts', 'products',
        'categories', 'pages', 'comments', 'messages',
    ];

    /**
     * Tablas del sistema que NUNCA deben buscarse.
     */
    private array $systemTables = [
        'site_security', 'login_attempts', 'blocked_ips',
        'sessions', 'active_sessions', 'banned_users',
    ];

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Establece las tablas permitidas para búsqueda.
     */
    public function setAllowedTables(array $tables): self
    {
        $this->allowedTables = $tables;
        return $this;
    }

    /**
     * Busca en todas las tablas permitidas.
     *
     * @param string $search Término de búsqueda
     * @return array Resultados agrupados por tabla
     */
    public function searchAllDB(string $search): array
    {
        $search = trim($search);

        // Validación de entrada
        if ($search === '' || strlen($search) < 2) {
            return ['error' => 'Search term must be at least 2 characters'];
        }

        if (strlen($search) > 100) {
            return ['error' => 'Search term too long (max 100 characters)'];
        }

        $results = [];
        $totalMatches = 0;

        try {
            // Obtener lista de tablas de la base de datos
            $stmt = $this->conn->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                // 🔒 WHITELIST: Solo buscar en tablas permitidas
                if (!in_array($table, $this->allowedTables, true)) {
                    continue;
                }

                // 🔒 BLACKLIST: Excluir tablas del sistema
                if (in_array($table, $this->systemTables, true)) {
                    continue;
                }

                // Obtener columnas de la tabla
                $stmt = $this->conn->prepare("SHOW COLUMNS FROM `{$table}`");
                $stmt->execute();
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if (empty($columns)) {
                    continue;
                }

                // Filtrar solo columnas de tipo texto
                $textColumns = $this->getTextColumns($table, $columns);

                if (empty($textColumns)) {
                    continue;
                }

                // Construir consulta con prepared statements
                $conditions = [];
                $params = [];
                $searchParam = '%' . $search . '%';

                foreach ($textColumns as $col) {
                    $conditions[] = "`{$col}` LIKE ?";
                    $params[] = $searchParam;
                }

                $sql = "SELECT * FROM `{$table}` WHERE " . implode(' OR ', $conditions) . " LIMIT 50";

                try {
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute($params);
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($rows)) {
                        $results[$table] = [
                            'count' => count($rows),
                            'rows' => $rows,
                        ];
                        $totalMatches += count($rows);
                    }
                } catch (PDOException $e) {
                    // Ignorar errores en tablas específicas, continuar con las demás
                    error_log("Search error in table {$table}: " . $e->getMessage());
                    continue;
                }
            }

            return [
                'success' => true,
                'total_matches' => $totalMatches,
                'tables_searched' => count($results),
                'results' => $results,
            ];
        } catch (PDOException $e) {
            error_log('SearchAllDB error: ' . $e->getMessage());
            return ['error' => 'Database error occurred'];
        }
    }

    /**
     * Obtiene las columnas de tipo texto de una tabla.
     */
    private function getTextColumns(string $table, array $columns): array
    {
        $textTypes = ['varchar', 'text', 'char', 'longtext', 'mediumtext', 'tinytext'];
        $textColumns = [];

        foreach ($columns as $col) {
            $stmt = $this->conn->prepare(
                "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = ? AND COLUMN_NAME = ?
                LIMIT 1"
            );
            $stmt->execute([$table, $col]);
            $type = $stmt->fetchColumn();

            if ($type && in_array(strtolower($type), $textTypes, true)) {
                $textColumns[] = $col;
            }
        }

        return $textColumns;
    }

    /**
     * Búsqueda rápida en una tabla específica (con whitelist).
     */
    public function searchInTable(string $table, string $search, array $columns = []): array
    {
        // 🔒 Validar tabla contra whitelist
        if (!in_array($table, $this->allowedTables, true)) {
            return ['error' => 'Table not allowed for search'];
        }

        $search = trim($search);
        if ($search === '' || strlen($search) < 2) {
            return ['error' => 'Search term too short'];
        }

        try {
            // Si no se especifican columnas, obtener las de texto
            if (empty($columns)) {
                $stmt = $this->conn->prepare("SHOW COLUMNS FROM `{$table}`");
                $stmt->execute();
                $allColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $columns = $this->getTextColumns($table, $allColumns);
            }

            if (empty($columns)) {
                return ['error' => 'No searchable columns found'];
            }

            $conditions = [];
            $params = [];
            $searchParam = '%' . $search . '%';

            foreach ($columns as $col) {
                $conditions[] = "`{$col}` LIKE ?";
                $params[] = $searchParam;
            }

            $sql = "SELECT * FROM `{$table}` WHERE " . implode(' OR ', $conditions) . " LIMIT 100";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);

            return [
                'success' => true,
                'count' => $stmt->rowCount(),
                'rows' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            ];
        } catch (PDOException $e) {
            error_log("SearchInTable error: " . $e->getMessage());
            return ['error' => 'Database error occurred'];
        }
    }
}
