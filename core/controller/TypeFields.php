<?php
declare(strict_types=1);

/**
 * Generador de campos de formulario dinámicos.
 *
 * CORRECCIONES CRÍTICAS:
 * - SQL Injection en $table eliminado (ahora usa prepared statements)
 * - mysqli_fetch_array reemplazado por PDO fetch
 * - Lista blanca de tablas permitidas
 * - Validación de tipos de columnas
 * - Sanitización de salida
 * - Inyección de dependencias PDO
 */
class TypeFields
{
    private PDO $conn;
    private string $table;

    /**
     * ✅ Lista blanca de tablas permitidas
     */
    private const ALLOWED_TABLES = [
        'products', 'categories', 'subcategories', 'brands',
        'customers', 'suppliers', 'orders', 'order_items',
        'users', 'pages', 'templates', 'settings'
    ];

    /**
     * ✅ Lista blanca de tipos de input
     */
    private const INPUT_TYPES = [
        1 => 'text',
        2 => 'number',
        3 => 'select',
        4 => 'textarea',
        5 => 'date',
        6 => 'file',
        7 => 'email',
        8 => 'password',
        9 => 'checkbox',
        10 => 'radio'
    ];

    public function __construct(PDO $connection, string $table)
    {
        // ✅ Validar tabla contra lista blanca
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            throw new InvalidArgumentException("Tabla no permitida: {$table}");
        }

        // ✅ Validar formato de nombre de tabla
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) {
            throw new InvalidArgumentException("Nombre de tabla inválido: {$table}");
        }

        $this->conn = $connection;
        $this->table = $table;
    }

    /**
     * Genera los campos del formulario.
     *
     * ✅ CORREGIDO: SQL Injection eliminado, ahora usa prepared statements
     */
    public function renderFields(string $excludeColumn = ''): void
    {
        // ✅ Consulta preparada (previene SQL Injection)
        $stmt = $this->conn->prepare(
            "SELECT col_name, col_type, input_type, joins, j_table, j_id, j_value
            FROM table_settings
            WHERE table_name = :table
            ORDER BY col_order ASC"
        );
        $stmt->execute([':table' => $this->table]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            echo '<p class="alert alert-warning">No hay configuración de campos para esta tabla.</p>';
            return;
        }

        foreach ($rows as $row) {
            $colName = $row['col_name'];
            $colType = $row['col_type'];
            $inputType = (int) $row['input_type'];
            $joins = $row['joins'] ?? '';
            $jTable = $row['j_table'] ?? '';
            $jId = $row['j_id'] ?? '';
            $jValue = $row['j_value'] ?? '';

            // ✅ Omitir columna excluida
            if ($colName === $excludeColumn) {
                continue;
            }

            // ✅ Sanitizar etiquetas
            $label = ucfirst(str_replace('_', ' ', $colName));
            $label = str_replace(' id', '', $label);
            $safeName = $this->escape($colName);
            $safeLabel = $this->escape($label);

            // ✅ Renderizar según tipo de columna
            if ($this->isNumericType($colType)) {
                if ($inputType === 3) {
                    $this->renderSelectField($safeName, $safeLabel, $jTable, $jId, $jValue);
                } else {
                    $this->renderTextField($safeName, $safeLabel, 'number');
                }
            } elseif ($this->isDateType($colType)) {
                $this->renderDateField($safeName, $safeLabel);
            } elseif ($this->isTextType($colType)) {
                if ($colName === 'imagen' || $colName === 'image') {
                    $this->renderFileField($safeName, $safeLabel);
                } else {
                    $this->renderTextField($safeName, $safeLabel, 'text');
                }
            } elseif ($this->isLongTextType($colType)) {
                $this->renderTextareaField($safeName, $safeLabel);
            } elseif ($colType === 'enum' || $colType === 'set') {
                $this->renderEnumField($safeName, $safeLabel, $colName);
            }
        }
    }

    /**
     * Renderiza un campo de texto.
     */
    private function renderTextField(string $name, string $label, string $type = 'text'): void
    {
        echo '<div class="form-group">';
        echo '<label for="' . $name . '">' . $label . ':</label>';
        echo '<input type="' . $this->escape($type) . '" class="form-control" id="' . $name . '" name="' . $name . '">';
        echo '</div>';
    }

    /**
     * Renderiza un campo select con datos de otra tabla.
     *
     * ✅ CORREGIDO: ahora usa prepared statements
     */
    private function renderSelectField(string $name, string $label, string $jTable, string $jId, string $jValue): void
    {
        // ✅ Validar tabla de join contra lista blanca
        if (!in_array($jTable, self::ALLOWED_TABLES, true)) {
            $this->renderTextField($name, $label, 'text');
            return;
        }

        // ✅ Validar nombres de columnas
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $jId) ||
            !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $jValue)) {
            $this->renderTextField($name, $label, 'text');
        return;
            }

            echo '<div class="form-group">';
            echo '<label for="' . $name . '">' . $label . ':</label>';
            echo '<select class="form-control" id="' . $name . '" name="' . $name . '">';
            echo '<option value="">-- Seleccionar --</option>';

            try {
                // ✅ Prepared statement (previene SQL Injection)
                $stmt = $this->conn->prepare("SELECT `{$jId}`, `{$jValue}` FROM `{$jTable}` ORDER BY `{$jValue}` ASC");
                $stmt->execute();

                while ($option = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $optId = $this->escape((string) $option[$jId]);
                    $optValue = $this->escape((string) $option[$jValue]);
                    echo '<option value="' . $optId . '">' . $optValue . '</option>';
                }
            } catch (PDOException $e) {
                error_log('TypeFields renderSelectField error: ' . $e->getMessage());
            }

            echo '</select>';
            echo '</div>';
    }

    /**
     * Renderiza un campo de fecha con datepicker.
     */
    private function renderDateField(string $name, string $label): void
    {
        echo '<div class="form-group">';
        echo '<label for="' . $name . '">' . $label . ':</label>';
        echo '<input type="date" class="form-control" id="' . $name . '" name="' . $name . '">';
        echo '</div>';
    }

    /**
     * Renderiza un campo textarea.
     */
    private function renderTextareaField(string $name, string $label): void
    {
        echo '<div class="form-group">';
        echo '<label for="' . $name . '">' . $label . ':</label>';
        echo '<textarea class="form-control" id="' . $name . '" name="' . $name . '" rows="4"></textarea>';
        echo '</div>';
    }

    /**
     * Renderiza un campo de archivo.
     */
    private function renderFileField(string $name, string $label): void
    {
        echo '<div class="form-group">';
        echo '<label for="' . $name . '">' . $label . ':</label>';
        echo '<div class="input-group">';
        echo '<div class="input-group-prepend"><span class="input-group-text">Subir</span></div>';
        echo '<div class="custom-file">';
        echo '<input type="file" class="custom-file-input" id="' . $name . '" name="' . $name . '">';
        echo '<label class="custom-file-label" for="' . $name . '">Elegir archivo</label>';
        echo '</div></div>';
        echo '<div id="preview"></div>';
        echo '</div>';
    }

    /**
     * Renderiza un campo enum/set.
     *
     * ✅ CORREGIDO: ahora usa prepared statements
     */
    private function renderEnumField(string $name, string $label, string $colName): void
    {
        try {
            // ✅ Prepared statement
            $stmt = $this->conn->prepare(
                "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = :table
            AND COLUMN_NAME = :col"
            );
            $stmt->execute([':table' => $this->table, ':col' => $colName]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $this->renderTextField($name, $label, 'text');
                return;
            }

            // ✅ Parsear valores enum/set
            $columnType = $row['COLUMN_TYPE'];
            $enumList = $this->parseEnumValues($columnType);

            echo '<div class="form-group">';
            echo '<label for="' . $name . '">' . $label . ':</label>';
            echo '<select class="form-control" id="' . $name . '" name="' . $name . '">';

            foreach ($enumList as $option) {
                $safeOption = $this->escape($option);
                echo '<option value="' . $safeOption . '">' . $safeOption . '</option>';
            }

            echo '</select>';
            echo '</div>';

        } catch (PDOException $e) {
            error_log('TypeFields renderEnumField error: ' . $e->getMessage());
            $this->renderTextField($name, $label, 'text');
        }
    }

    /**
     * Parsea valores enum/set de MySQL.
     */
    private function parseEnumValues(string $columnType): array
    {
        // Extraer valores entre paréntesis: enum('a','b','c')
        if (preg_match("/^(enum|set)\('(.+)'\)$/i", $columnType, $matches)) {
            $values = explode("','", $matches[2]);
            return array_map('trim', $values);
        }
        return [];
    }

    private function isNumericType(string $type): bool
    {
        return in_array($type, [
            'int', 'tinyint', 'smallint', 'mediumint', 'bigint',
            'bit', 'float', 'double', 'decimal'
        ], true);
    }

    private function isDateType(string $type): bool
    {
        return in_array($type, ['date', 'datetime', 'timestamp', 'time', 'year'], true);
    }

    private function isTextType(string $type): bool
    {
        return in_array($type, ['varchar', 'char'], true);
    }

    private function isLongTextType(string $type): bool
    {
        return in_array($type, ['text', 'tinytext', 'mediumtext', 'longtext', 'json'], true);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
