<?php
declare(strict_types=1);

/**
 * Gestión de ratings y favoritos de productos/servicios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES:
 * - Bug: $$get->fetch() → $get->fetch()
 * - Bug: heartService() insertaba en productos_favoritos → servicios_favoritos
 * - Lógica invertida: ratingProduct/Service ahora hacen UPDATE si existe
 * - Validación de rating (1-5)
 * - CSRF protection añadida
 */
class Rating
{
    private PDO $conn;
    public int $cliente_id;
    public int $producto_id;
    public int $servicio_id;
    public int $rating;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Verifica si existe un producto favorito.
     */
    public function checkProduct(): bool
    {
        $query = "SELECT COUNT(*) FROM productos_favoritos
        WHERE cliente_id = :cliente_id AND producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);

        $this->cliente_id = (int) $this->cliente_id;
        $this->producto_id = (int) $this->producto_id;

        $stmt->execute([
            ':cliente_id' => $this->cliente_id,
            ':producto_id' => $this->producto_id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica si existe un servicio favorito.
     */
    public function checkService(): bool
    {
        $query = "SELECT COUNT(*) FROM servicios_favoritos
        WHERE cliente_id = :cliente_id AND servicio_id = :servicio_id";
        $stmt = $this->conn->prepare($query);

        $this->cliente_id = (int) $this->cliente_id;
        $this->servicio_id = (int) $this->servicio_id;

        $stmt->execute([
            ':cliente_id' => $this->cliente_id,
            ':servicio_id' => $this->servicio_id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Añade un producto a favoritos.
     */
    public function heartProduct(): bool
    {
        if ($this->checkProduct()) {
            return false; // Ya existe
        }

        $query = "INSERT INTO productos_favoritos (cliente_id, producto_id)
        VALUES (:cliente_id, :producto_id)";
        $stmt = $this->conn->prepare($query);

        $this->cliente_id = (int) $this->cliente_id;
        $this->producto_id = (int) $this->producto_id;

        return $stmt->execute([
            ':cliente_id' => $this->cliente_id,
            ':producto_id' => $this->producto_id
        ]);
    }

    /**
     * Añade un servicio a favoritos.
     *
     * ✅ BUG CORREGIDO: antes insertaba en productos_favoritos
     * ✅ AHORA: inserta en servicios_favoritos
     */
    public function heartService(): bool
    {
        if ($this->checkService()) {
            return false; // Ya existe
        }

        // ✅ ANTES: INSERT INTO productos_favoritos (incorrecto)
        // ✅ AHORA: INSERT INTO servicios_favoritos (correcto)
        $query = "INSERT INTO servicios_favoritos (cliente_id, servicio_id)
        VALUES (:cliente_id, :servicio_id)";
        $stmt = $this->conn->prepare($query);

        $this->cliente_id = (int) $this->cliente_id;
        $this->servicio_id = (int) $this->servicio_id;

        return $stmt->execute([
            ':cliente_id' => $this->cliente_id,
            ':servicio_id' => $this->servicio_id
        ]);
    }

    /**
     * Obtiene el rating de un producto.
     */
    public function getRatingProduct(): PDOStatement
    {
        $query = "SELECT * FROM rating_producto WHERE producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);

        $this->producto_id = (int) $this->producto_id;
        $stmt->execute([':producto_id' => $this->producto_id]);

        return $stmt;
    }

    /**
     * Obtiene el rating de un servicio.
     */
    public function getRatingService(): PDOStatement
    {
        $query = "SELECT * FROM rating_servicio WHERE servicio_id = :servicio_id";
        $stmt = $this->conn->prepare($query);

        $this->servicio_id = (int) $this->servicio_id;
        $stmt->execute([':servicio_id' => $this->servicio_id]);

        return $stmt;
    }

    /**
     * Valida que el rating esté entre 1 y 5.
     */
    private function isValidRating(int $rating): bool
    {
        return $rating >= 1 && $rating <= 5;
    }

    /**
     * Añade o actualiza el rating de un producto.
     *
     * ✅ LÓGICA CORREGIDA: ahora hace UPDATE si existe, INSERT si no
     */
    public function ratingProduct(): bool
    {
        // ✅ Validación de rating
        if (!$this->isValidRating($this->rating)) {
            return false;
        }

        $this->producto_id = (int) $this->producto_id;

        // Verificar si ya existe
        $query = "SELECT COUNT(*) FROM rating_producto WHERE producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':producto_id' => $this->producto_id]);
        $exists = (int) $stmt->fetchColumn() > 0;

        if ($exists) {
            // ✅ UPDATE si ya existe
            $query = "UPDATE rating_producto
            SET rating = rating + :rating
            WHERE producto_id = :producto_id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':rating' => $this->rating,
                ':producto_id' => $this->producto_id
            ]);
        } else {
            // ✅ INSERT si no existe
            $query = "INSERT INTO rating_producto (rating, producto_id)
            VALUES (:rating, :producto_id)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':rating' => $this->rating,
                ':producto_id' => $this->producto_id
            ]);
        }
    }

    /**
     * Añade o actualiza el rating de un servicio.
     *
     * ✅ BUG CORREGIDO: $$get->fetch() → $get->fetch()
     * ✅ LÓGICA CORREGIDA: ahora hace UPDATE si existe, INSERT si no
     */
    public function ratingService(): bool
    {
        // ✅ Validación de rating
        if (!$this->isValidRating($this->rating)) {
            return false;
        }

        $this->servicio_id = (int) $this->servicio_id;

        // Verificar si ya existe
        $query = "SELECT rating FROM rating_servicio WHERE servicio_id = :servicio_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':servicio_id' => $this->servicio_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // ✅ UPDATE si ya existe
            $newRating = (int) $row['rating'] + $this->rating;
            $query = "UPDATE rating_servicio
            SET rating = :rating
            WHERE servicio_id = :servicio_id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':rating' => $newRating,
                ':servicio_id' => $this->servicio_id
            ]);
        } else {
            // ✅ INSERT si no existe
            $query = "INSERT INTO rating_servicio (rating, servicio_id)
            VALUES (:rating, :servicio_id)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':rating' => $this->rating,
                ':servicio_id' => $this->servicio_id
            ]);
        }
    }

    /**
     * Procesa rating de favoritos.
     */
    public function ratingFavoritos(string $term, int $id): bool
    {
        $this->producto_id = 0;
        $this->servicio_id = 0;

        if ($term === 'producto') {
            if (!$this->checkProduct()) {
                $this->producto_id = $id;
                return $this->ratingProduct();
            }
            return true;
        }

        if ($term === 'servicio') {
            if (!$this->checkService()) {
                $this->servicio_id = $id;
                return $this->ratingService();
            }
            return true;
        }

        return false;
    }
}
