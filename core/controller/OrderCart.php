<?php
//
//  This application develop by PEPIUOX.
//  Already using PDO - Security hardening applied
//
class OrderCart {
    protected $conn;
    private $table_name  = 'orden';
    private $table_items = 'canasta_articulos';

    public $session_key;
    public $orden_id;
    public $producto_id;
    public $precio_articulo;
    public $cantidad;
    public $monto_articulos;
    private $cliente_id;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /** Generador seguro de ID de orden */
    public function idOrder(int $len = 16): string {
        $bytes = random_bytes(16);
        return bin2hex($bytes) . substr(hash('sha256', random_bytes(21)), 0, $len);
    }

    /** Escapa HTML de forma segura (reemplaza strip_tags) */
    private function sanitize(?string $value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function orderItems(): PDOStatement {
        $query = "SELECT * FROM {$this->table_items} ca
        LEFT JOIN productos p ON ca.producto_id = p.idPrd
        WHERE session_key = :session_key";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':session_key' => $this->sanitize($this->session_key)]);
        return $stmt;
    }

    public function checkOrder(): bool {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE session_id = :session_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':session_id' => $this->sanitize($this->session_key)]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function updateOrder(): bool {
        $query = "UPDATE {$this->table_name}
        SET cantidad = :cantidad
        WHERE session_key = :session_key AND producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':cantidad'    => $this->sanitize($this->cantidad),
                              ':session_key' => $this->sanitize($this->session_key),
                              ':producto_id' => $this->sanitize($this->producto_id),
        ]);
    }

    public function order(string $orden_id, $monto_total): bool {
        $query = "INSERT INTO {$this->table_name}
        SET session_id = :session_id, orden_id = :orden_id,
        cliente_id = :cliente_id, monto_total = :monto_total";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':session_id'  => $this->sanitize($this->session_key),
                              ':orden_id'    => $this->sanitize($orden_id),
                              ':cliente_id'  => $this->sanitize($this->cliente_id),
                              ':monto_total' => $monto_total, // numérico, no necesita escape HTML
        ]);
    }

    public function purchaseOrder(string $orden_id, $monto_total): bool {
        $query = "INSERT INTO orden_compra
        SET session_id = :session_id, orden_id = :orden_id,
        cliente_id = :cliente_id, monto_total = :monto_total";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':session_id'  => $this->sanitize($this->session_key),
                              ':orden_id'    => $this->sanitize($orden_id),
                              ':cliente_id'  => $this->sanitize($this->cliente_id),
                              ':monto_total' => $monto_total,
        ]);
    }

    public function saleOrder(string $orden_id, string $producto_id, $precio_articulo, $cantidad, $monto_articulos): bool {
        $query = "INSERT INTO orden_articulos
        SET orden_id = :orden_id, producto_id = :producto_id,
        precio_articulo = :precio_articulo, cantidad = :cantidad,
        monto_articulos = :monto_articulos, cliente_id = :cliente_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':orden_id'        => $this->sanitize($orden_id),
                              ':producto_id'     => $this->sanitize($producto_id),
                              ':precio_articulo' => $precio_articulo,
                              ':cantidad'        => $cantidad,
                              ':monto_articulos' => $monto_articulos,
                              ':cliente_id'      => $this->sanitize($this->cliente_id),
        ]);
    }

    public function process(): PDOStatement {
        $query = "SELECT * FROM orden_compra WHERE session_id = :session_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':session_id' => $this->sanitize($this->session_key)]);
        return $stmt;
    }
}
