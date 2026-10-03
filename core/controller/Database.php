<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Wrapper de base de datos usando PDO.
 * Mantiene la misma API que la versión MySQLi para compatibilidad.
 */
class Database
{
    private static ?self $instance = null;
    private PDO $pdo;

    public function __construct(string $dsn, string $user, string $password, array $options = [])
    {
        $defaultOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $user, $password, $options + $defaultOptions);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            $cfg = config('database');
            self::$instance = new self(
                "mysql:host={$cfg['host']};dbname={$cfg['database']};charset={$cfg['charset']}",
                $cfg['username'],
                $cfg['password']
            );
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Ejecuta una consulta con parámetros.
     * Retorna un PDOStatement (compatible con ->rowCount()).
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtiene una sola fila.
     */
    public function selectOne(string $sql, array $params = []): ?object
    {
        $stmt = $this->query($sql, $params);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row !== false ? $row : null;
    }

    /**
     * Obtiene múltiples filas.
     */
    public function select(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Inserta un registro y retorna el ID generado.
     */
    public function insert(string $table, array $data): int|string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));

        return $this->pdo->lastInsertId();
    }

    /**
     * Actualiza registros.
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));
        $sql = "UPDATE `{$table}` SET {$set} WHERE {$where}";
        $stmt = $this->query($sql, array_merge(array_values($data), $whereParams));
        return $stmt->rowCount();
    }

    /**
     * Elimina registros.
     */
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
}
