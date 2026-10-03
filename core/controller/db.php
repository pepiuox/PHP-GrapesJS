<?php
declare(strict_types=1);

/**
 * Wrapper de base de datos con PDO.
 * Mantiene la misma API que la versión MySQLi para compatibilidad.
 *
 * Uso:
 *   $db = new db($pdo);
 *   $db->query("SELECT * FROM users WHERE id = ?", $userId)->fetchAll();
 */
class db
{
    protected PDO $connection;
    protected ?PDOStatement $query = null;
    public int $query_count = 0;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    /**
     * Ejecuta una consulta con parámetros opcionales.
     *
     * @param string $query SQL con placeholders (?)
     * @param mixed ...$params Parámetros a vincular
     * @return self
     */
    public function query(string $query, ...$params): self
    {
        try {
            $this->query = $this->connection->prepare($query);

            if (!empty($params)) {
                // Aplanar arrays anidados (compatibilidad con versión anterior)
                $flatParams = [];
                foreach ($params as $param) {
                    if (is_array($param)) {
                        foreach ($param as $p) {
                            $flatParams[] = $p;
                        }
                    } else {
                        $flatParams[] = $param;
                    }
                }

                $this->query->execute($flatParams);
            } else {
                $this->query->execute();
            }

            $this->query_count++;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw new RuntimeException('Database query failed: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Obtiene todos los resultados como array asociativo.
     */
    public function fetchAll(): array
    {
        if ($this->query === null) {
            return [];
        }
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una sola fila como array asociativo.
     */
    public function fetchArray(): array
    {
        if ($this->query === null) {
            return [];
        }
        $row = $this->query->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : [];
    }

    /**
     * Obtiene una sola fila como objeto.
     */
    public function fetchObject(): ?object
    {
        if ($this->query === null) {
            return null;
        }
        $row = $this->query->fetch(PDO::FETCH_OBJ);
        return $row !== false ? $row : null;
    }

    /**
     * Cuenta el número de filas afectadas/devueltas.
     */
    public function numRows(): int
    {
        if ($this->query === null) {
            return 0;
        }
        return $this->query->rowCount();
    }

    /**
     * Obtiene el ID del último insert.
     */
    public function insertedId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Cierra el cursor del statement.
     */
    public function close(): bool
    {
        if ($this->query !== null) {
            $this->query->closeCursor();
            $this->query = null;
        }
        return true;
    }

    /**
     * Obtiene el número de filas afectadas.
     */
    public function affectedRows(): int
    {
        if ($this->query === null) {
            return 0;
        }
        return $this->query->rowCount();
    }

    /**
     * Obtiene la conexión PDO subyacente.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Inicia una transacción.
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Confirma una transacción.
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Revierte una transacción.
     */
    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * Verifica si hay una transacción activa.
     */
    public function inTransaction(): bool
    {
        return $this->connection->inTransaction();
    }
}
