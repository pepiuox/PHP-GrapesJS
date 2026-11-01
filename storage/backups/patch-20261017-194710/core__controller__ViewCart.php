<?php
declare(strict_types=1);

class ViewCart
{
    private string $tableName = 'canasta_articulos';
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Devuelve el número de artículos en el carrito.
     */
    public function count(string $session): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tableName} WHERE session_key = :sk"
        );
        $stmt->execute([':sk' => $session]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Devuelve el detalle del carrito con subtotal.
     */
    public function read(string $session): PDOStatement
    {
        $session = htmlspecialchars(strip_tags($session), ENT_QUOTES, 'UTF-8');

        $sql = "SELECT p.idPrd, p.producto, p.precio, ci.cantidad,
        (ci.cantidad * p.precio) AS subtotal
        FROM {$this->tableName} ci
        LEFT JOIN productos p ON ci.producto_id = p.idPrd
        WHERE ci.session_key = :sk";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':sk' => $session]);
        return $stmt;
    }
}
