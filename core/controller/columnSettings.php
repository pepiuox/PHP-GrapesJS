<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class ColumnSettings
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function setList(int $value): bool
    {
        return $value === 1;
    }

    public function setView(int $value): bool
    {
        return $value === 1;
    }

    public function setAdd(int $value): bool
    {
        return $value === 1;
    }

    public function setUpdate(int $value): bool
    {
        return $value === 1;
    }

    /**
     * Obtiene la configuración de una columna específica.
     *
     * @return string|null JSON con la configuración o null si no existe
     */
    public function colSettings(string $tname, string $cname): ?string
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM table_column_settings
            WHERE name_table = :tname AND col_name = :cname
            LIMIT 1"
        );
        $stmt->execute([
            ':tname' => $tname,
            ':cname' => $cname,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return json_encode($row, JSON_UNESCAPED_UNICODE);
        }

        return null;
    }
}
