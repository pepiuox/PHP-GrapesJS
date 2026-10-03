<?php
declare(strict_types=1);

/**
 * Creador de consultas dinámicas.
 *
 * ✅ CORRECCIONES CRÍTICAS DE SEGURIDAD:
 * - Validación estricta de nombres de tablas y bases de datos
 * - Lista blanca de caracteres permitidos
 * - Prevención de inyección SQL en metadatos
 * - Uso de prepared statements para consultas de información_schema
 */
class QueryCreator
{
    private PDO $conn;
    private string $DBname;
    public string $table;

    public function __construct(PDO $connection, string $dbName = 'ecommerce', string $table = 'proveedores')
    {
        $this->conn = $connection;

        // ✅ Validación estricta de nombres
        $this->setDBname($dbName);
        $this->setTable($table);
    }

    /**
     * Establece el nombre de la base de datos con validación.
     */
    public function setDBname(string $dbName): void
    {
        // ✅ Validar formato: solo letras, números y guiones bajos
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $dbName)) {
            throw new InvalidArgumentException('Nombre de base de datos inválido');
        }
        if (strlen($dbName) > 64) {
            throw new InvalidArgumentException('Nombre de base de datos demasiado largo');
        }
        $this->DBname = $dbName;
    }

    /**
     * Establece el nombre de la tabla con validación.
     */
    public function setTable(string $table): void
    {
        // ✅ Validar formato: solo letras, números y guiones bajos
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new InvalidArgumentException('Nombre de tabla inválido');
        }
        if (strlen($table) > 64) {
            throw new InvalidArgumentException('Nombre de tabla demasiado largo');
        }
        $this->table = $table;
    }

    /**
     * Obtiene las columnas de la tabla.
     *
     * ✅ AHORA usa prepared statements para prevenir inyección
     */
    public function getColumns(): array
    {
        $query = "SELECT COLUMN_NAME AS name, DATA_TYPE AS type, IS_NULLABLE AS nullable
        FROM information_schema.columns
        WHERE table_schema = :db AND table_name = :table
        ORDER BY ORDINAL_POSITION";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':db' => $this->DBname,
            ':table' => $this->table
        ]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Genera código PHP para INSERT.
     *
     * ✅ SEGURIDAD: Validación de nombres de columnas
     */
    public function generateInsertCode(): string
    {
        $columns = $this->getColumns();

        if (empty($columns)) {
            throw new RuntimeException('No se encontraron columnas en la tabla');
        }

        $content = "<?php\n";
        $content .= "// Código generado automáticamente para tabla: {$this->table}\n";
        $content .= "// Generado: " . date('Y-m-d H:i:s') . "\n\n";

        // Variables
        $content .= "// Variables\n";
        foreach ($columns as $col) {
            // ✅ Validar nombre de columna
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            $content .= "\${$col->name} = \$_POST['{$col->name}'] ?? null;\n";
        }
        $content .= "\n";

        // Sanitización
        $content .= "// Sanitización\n";
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            $content .= "\${$col->name} = htmlspecialchars(strip_tags((string) \${$col->name}), ENT_QUOTES, 'UTF-8');\n";
        }
        $content .= "\n";

        // Query
        $content .= "// Query\n";
        $setParts = [];
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            $setParts[] = "{$col->name} = :{$col->name}";
        }
        $setClause = implode(', ', $setParts);
        $content .= "\$query = \"INSERT INTO {$this->table} SET {$setClause}\";\n\n";

        // Prepare
        $content .= "// Prepare statement\n";
        $content .= "\$stmt = \$this->conn->prepare(\$query);\n\n";

        // Bind parameters
        $content .= "// Bind parameters\n";
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            $content .= "\$stmt->bindParam(':{$col->name}', \${$col->name});\n";
        }
        $content .= "\n";

        // Execute
        $content .= "// Execute\n";
        $content .= "if (\$stmt->execute()) {\n";
        $content .= "    return true;\n";
        $content .= "} else {\n";
        $content .= "    return false;\n";
        $content .= "}\n";

        return $content;
    }

    /**
     * Genera código PHP para SELECT.
     */
    public function generateSelectCode(): string
    {
        $columns = $this->getColumns();

        if (empty($columns)) {
            throw new RuntimeException('No se encontraron columnas en la tabla');
        }

        $content = "<?php\n";
        $content .= "// Código generado automáticamente para tabla: {$this->table}\n";
        $content .= "// Generado: " . date('Y-m-d H:i:s') . "\n\n";

        // Query
        $content .= "// Query\n";
        $colNames = array_map(fn($col) => $col->name, $columns);
        $colList = implode(', ', $colNames);
        $content .= "\$query = \"SELECT {$colList} FROM {$this->table} WHERE id = :id\";\n\n";

        // Prepare
        $content .= "// Prepare statement\n";
        $content .= "\$stmt = \$this->conn->prepare(\$query);\n";
        $content .= "\$stmt->bindParam(':id', \$id, PDO::PARAM_INT);\n\n";

        // Execute
        $content .= "// Execute\n";
        $content .= "\$stmt->execute();\n";
        $content .= "\$result = \$stmt->fetch(PDO::FETCH_ASSOC);\n\n";

        $content .= "return \$result;\n";

        return $content;
    }

    /**
     * Genera código PHP para UPDATE.
     */
    public function generateUpdateCode(): string
    {
        $columns = $this->getColumns();

        if (empty($columns)) {
            throw new RuntimeException('No se encontraron columnas en la tabla');
        }

        $content = "<?php\n";
        $content .= "// Código generado automáticamente para tabla: {$this->table}\n";
        $content .= "// Generado: " . date('Y-m-d H:i:s') . "\n\n";

        // Variables
        $content .= "// Variables\n";
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            if ($col->name === 'id') continue;
            $content .= "\${$col->name} = \$_POST['{$col->name}'] ?? null;\n";
        }
        $content .= "\n";

        // Sanitización
        $content .= "// Sanitización\n";
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            if ($col->name === 'id') continue;
            $content .= "\${$col->name} = htmlspecialchars(strip_tags((string) \${$col->name}), ENT_QUOTES, 'UTF-8');\n";
        }
        $content .= "\n";

        // Query
        $content .= "// Query\n";
        $setParts = [];
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            if ($col->name === 'id') continue;
            $setParts[] = "{$col->name} = :{$col->name}";
        }
        $setClause = implode(', ', $setParts);
        $content .= "\$query = \"UPDATE {$this->table} SET {$setClause} WHERE id = :id\";\n\n";

        // Prepare
        $content .= "// Prepare statement\n";
        $content .= "\$stmt = \$this->conn->prepare(\$query);\n\n";

        // Bind parameters
        $content .= "// Bind parameters\n";
        foreach ($columns as $col) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $col->name)) {
                continue;
            }
            if ($col->name === 'id') continue;
            $content .= "\$stmt->bindParam(':{$col->name}', \${$col->name});\n";
        }
        $content .= "\$stmt->bindParam(':id', \$id, PDO::PARAM_INT);\n\n";

        // Execute
        $content .= "// Execute\n";
        $content .= "if (\$stmt->execute()) {\n";
        $content .= "    return true;\n";
        $content .= "} else {\n";
        $content .= "    return false;\n";
        $content .= "}\n";

        return $content;
    }
}
