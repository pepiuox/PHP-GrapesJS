<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class CheckCart
{
    protected PDO $conn;
    private string $table_name = "canasta_articulos";

    public int $idCnt = 0;
    public $session;
    public string $session_key = '';
    public int $producto_id = 0;
    public int $cantidad = 0;
    public int $cliente_id = 0;
    public string $creado = '';
    public string $modificado = '';

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Verifica si un item existe en el carrito.
     */
    public function exists(): bool
    {
        if (isset($_SESSION["client_id"])) {
            $query = "SELECT COUNT(*) FROM {$this->table_name}
            WHERE session_key = :session_key
            AND producto_id = :producto_id
            AND cliente_id = :cliente_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':session_key'  => $this->session_key,
                ':producto_id'  => $this->producto_id,
                ':cliente_id'   => $this->cliente_id,
            ]);
        } else {
            $query = "SELECT COUNT(*) FROM {$this->table_name}
            WHERE session_key = :session_key AND producto_id = :producto_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':session_key' => $this->session_key,
                ':producto_id' => $this->producto_id,
            ]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Cuenta los items en el carrito.
     */
    public function count(): int
    {
        if (isset($_SESSION["client_id"])) {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM {$this->table_name} WHERE cliente_id = :cliente_id"
            );
            $stmt->execute([':cliente_id' => $this->cliente_id]);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM {$this->table_name} WHERE session_key = :session_key"
            );
            $stmt->execute([':session_key' => $this->session_key]);
        }

        return (int) $stmt->fetchColumn();
    }

    /**
     * Crea un item en el carrito para usuario logueado.
     */
    public function createUser(): bool
    {
        $this->creado = date("Y-m-d H:i:s");

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table_name}
            SET producto_id = :producto_id, cantidad = :cantidad,
            cliente_id = :cliente_id, creado = :creado"
        );
        return $stmt->execute([
            ':producto_id' => $this->producto_id,
            ':cantidad'    => $this->cantidad,
            ':cliente_id'  => $this->cliente_id,
            ':creado'      => $this->creado,
        ]);
    }

    /**
     * Crea un item en el carrito para sesión de invitado.
     */
    public function createSession(): bool
    {
        $this->creado = date("Y-m-d H:i:s");

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table_name}
            SET producto_id = :producto_id, cantidad = :cantidad,
            session_key = :session_key, creado = :creado"
        );
        return $stmt->execute([
            ':producto_id' => $this->producto_id,
            ':cantidad'    => $this->cantidad,
            ':session_key' => $this->session_key,
            ':creado'      => $this->creado,
        ]);
    }

    /**
     * Lee los items del carrito.
     */
    public function read(): PDOStatement
    {
        $query = "SELECT p.idPrd, p.producto, p.precio, ci.cantidad,
        ci.cantidad * p.precio AS subtotal
        FROM {$this->table_name} ci
        LEFT JOIN productos p ON ci.producto_id = p.idPrd
        WHERE ci.session_key = :session_key";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':session_key' => $this->session_key]);
        return $stmt;
    }

    /**
     * Actualiza la cantidad de un item.
     */
    public function update(): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table_name}
            SET cantidad = :cantidad
            WHERE producto_id = :producto_id AND session_key = :session_key"
        );
        return $stmt->execute([
            ':cantidad'    => $this->cantidad,
            ':producto_id' => $this->producto_id,
            ':session_key' => $this->session_key,
        ]);
    }

    /**
     * Elimina un item específico.
     */
    public function delete(): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table_name}
            WHERE session_key = :session_key AND producto_id = :producto_id"
        );
        return $stmt->execute([
            ':session_key' => $this->session_key,
            ':producto_id' => $this->producto_id,
        ]);
    }

    /**
     * Elimina todos los items de un usuario.
     */
    public function deleteByUser(): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table_name} WHERE cliente_id = :cliente_id"
        );
        return $stmt->execute([':cliente_id' => $this->cliente_id]);
    }

    /**
     * Elimina todos los items de una sesión.
     */
    public function deleteBySession(): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table_name} WHERE session_key = :session_key"
        );
        return $stmt->execute([':session_key' => $this->session_key]);
    }
}
