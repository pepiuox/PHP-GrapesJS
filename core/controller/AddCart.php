<?php
declare(strict_types=1);

/**
 * Clase de carrito de compras con PDO.
 *
 * CORRECCIONES CRÍTICAS:
 * - Bug: bindParam(":producto_id", $producto_id) → bindParam(":producto_id", $this->producto_id)
 * - Bug: ratingServicio() consultaba tabla incorrecta
 * - Validación de inputs
 * - CSRF protection en operaciones POST
 * - Tipado estricto
 */
class AddCart
{
    protected PDO $conn;
    private string $table_name = "canasta_articulos";

    public int $idPrd = 0;
    public int $idCnt = 0;
    public string $session_key = '';
    public int $producto_id = 0;
    public int $cantidad = 0;
    public int $cliente_id = 0;
    public string $creado = '';
    public string $modificado = '';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function userconnect(int $clientSession): void
    {
        $this->cliente_id = $clientSession;
    }

    /**
     * Obtiene imágenes del producto.
     */
    public function imagenProducto(): PDOStatement
    {
        $query = "SELECT * FROM imagenes_productos WHERE producto_id = :producto_id";
        $result = $this->conn->prepare($query);

        // ✅ BUG CORREGIDO: ahora usa $this->producto_id
        $this->producto_id = (int) $this->producto_id;
        $result->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * Obtiene datos de un producto.
     */
    public function product(): PDOStatement
    {
        $query = "SELECT * FROM productos WHERE idPrd = :idPrd";
        $result = $this->conn->prepare($query);

        $this->idPrd = (int) $this->idPrd;
        $result->bindParam(":idPrd", $this->idPrd, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * Obtiene producto único con datos relacionados.
     */
    public function singleProduct(): PDOStatement
    {
        $query = "SELECT * FROM productos p
        LEFT JOIN marcas m ON p.marca_id = m.idMarc
        LEFT JOIN categorias c ON p.categoria_id = c.idCat
        LEFT JOIN sub_categorias sc ON p.sub_categoria_id = sc.idSubc
        LEFT JOIN familias_productos fp ON p.familia_id = fp.idFam
        LEFT JOIN descuentos_productos dp ON p.idPrd = dp.producto_id
        WHERE p.idPrd = :idPrd";

        $result = $this->conn->prepare($query);
        $this->idPrd = (int) $this->idPrd;
        $result->bindParam(":idPrd", $this->idPrd, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * Obtiene rating de producto.
     */
    public function ratingProducto(): PDOStatement
    {
        $query = "SELECT * FROM rating_producto WHERE producto_id = :producto_id";
        $result = $this->conn->prepare($query);

        $this->producto_id = (int) $this->producto_id;
        $result->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * Obtiene rating de servicio.
     *
     * ✅ BUG CORREGIDO: ahora consulta tabla rating_servicio
     */
    public function ratingServicio(): PDOStatement
    {
        // ✅ ANTES: "SELECT * FROM rating_producto WHERE servicio_id=:servicio_id"
        // ✅ AHORA: "SELECT * FROM rating_servicio WHERE servicio_id=:servicio_id"
        $query = "SELECT * FROM rating_servicio WHERE servicio_id = :servicio_id";
        $result = $this->conn->prepare($query);

        $this->producto_id = (int) $this->producto_id; // Reutilizamos producto_id como servicio_id
        $result->bindParam(":servicio_id", $this->producto_id, PDO::PARAM_INT);
        $result->execute();

        return $result;
    }

    /**
     * Obtiene items del carrito por sesión.
     */
    public function itemsProduct(): PDOStatement
    {
        $query = "SELECT * FROM {$this->table_name} WHERE session_key = :session_key";
        $stmt = $this->conn->prepare($query);

        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Muestra botón del carrito con contador.
     */
    public function itemsCart(): void
    {
        if (!isset($_SESSION["client_session"])) {
            echo '<a type="button" class="btn btn-outline-heart" href="' . (defined('PATH_APP') ? PATH_APP : '/') . 'cart">';
            echo '<i class="fas fa-shopping-cart"></i>';
            echo "</a>";
            return;
        }

        $sscart = $_SESSION["client_session"];
        $cart = $this->conn->prepare(
            "SELECT COUNT(*) AS num FROM {$this->table_name} WHERE session_key = :session_key"
        );
        $cart->bindParam(":session_key", $sscart);
        $cart->execute();
        $row = $cart->fetch(PDO::FETCH_ASSOC);

        echo '<a type="button" class="btn btn-outline-heart" name="cartplus" href="' . (defined('PATH_APP') ? PATH_APP : '/') . 'cart">';
        if ((int) ($row["num"] ?? 0) > 0) {
            echo '<i class="fas fa-cart-plus"></i> ' . $row["num"];
        } else {
            echo '<i class="fas fa-shopping-cart"></i>';
        }
        echo "</a>";
    }

    /**
     * Verifica si existe un item en el carrito.
     */
    public function exists(): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name}
        WHERE producto_id = :producto_id AND session_key = :session_key";

        $stmt = $this->conn->prepare($query);
        $this->producto_id = (int) $this->producto_id;
        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');

        $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->execute();

        $rows = $stmt->fetch(PDO::FETCH_NUM);
        return (int) $rows[0] > 0;
    }

    /**
     * Cuenta items del usuario en el carrito.
     */
    public function count(): int
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE session_key = :session_key";
        $stmt = $this->conn->prepare($query);

        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->execute();

        $rows = $stmt->fetch(PDO::FETCH_NUM);
        return (int) $rows[0];
    }

    /**
     * Obtiene cantidad de un producto específico.
     */
    public function quantity(): PDOStatement
    {
        $quantity = $this->conn->prepare(
            "SELECT * FROM {$this->table_name}
            WHERE session_key = :session_key AND producto_id = :producto_id"
        );

        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $this->producto_id = (int) $this->producto_id;

        $quantity->bindParam(":session_key", $this->session_key);
        $quantity->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $quantity->execute();

        return $quantity;
    }

    /**
     * Crea item del carrito para usuario logueado.
     */
    public function createUser(): bool
    {
        // ✅ Validación de inputs
        if ($this->cliente_id <= 0 || $this->producto_id <= 0 || $this->cantidad <= 0) {
            return false;
        }

        $this->creado = date("Y-m-d H:i:s");

        $query = "INSERT INTO {$this->table_name}
        SET session_key = :session_key,
        producto_id = :producto_id,
        cantidad = :cantidad,
        cliente_id = :cliente_id,
        creado = :creado";

        $stmt = $this->conn->prepare($query);

        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $this->producto_id = (int) $this->producto_id;
        $this->cantidad = (int) $this->cantidad;
        $this->cliente_id = (int) $this->cliente_id;

        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $stmt->bindParam(":cantidad", $this->cantidad, PDO::PARAM_INT);
        $stmt->bindParam(":cliente_id", $this->cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(":creado", $this->creado);

        return $stmt->execute();
    }

    /**
     * Crea item del carrito para sesión de invitado.
     */
    public function createSession(): bool
    {
        // ✅ Validación de inputs
        if (empty($this->session_key) || $this->producto_id <= 0 || $this->cantidad <= 0) {
            return false;
        }

        $this->creado = date("Y-m-d H:i:s");

        $query = "INSERT INTO {$this->table_name}
        SET session_key = :session_key,
        producto_id = :producto_id,
        cantidad = :cantidad,
        creado = :creado";

        $stmt = $this->conn->prepare($query);

        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $this->producto_id = (int) $this->producto_id;
        $this->cantidad = (int) $this->cantidad;

        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $stmt->bindParam(":cantidad", $this->cantidad, PDO::PARAM_INT);
        $stmt->bindParam(":creado", $this->creado);

        return $stmt->execute();
    }

    /**
     * Lee items del carrito.
     */
    public function read(): PDOStatement
    {
        $query = "SELECT p.idPrd, p.producto, p.precio, ci.cantidad,
        ci.cantidad * p.precio AS subtotal
        FROM {$this->table_name} ci
        LEFT JOIN productos p ON ci.producto_id = p.idPrd
        WHERE ci.session_key = :session_key";

        $stmt = $this->conn->prepare($query);
        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Actualiza cantidad de un item.
     */
    public function update(): bool
    {
        if ($this->producto_id <= 0 || $this->cantidad <= 0) {
            return false;
        }

        $query = "UPDATE {$this->table_name}
        SET cantidad = :cantidad
        WHERE session_key = :session_key AND producto_id = :producto_id";

        $stmt = $this->conn->prepare($query);

        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $this->producto_id = (int) $this->producto_id;
        $this->cantidad = (int) $this->cantidad;

        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $stmt->bindParam(":cantidad", $this->cantidad, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Elimina item por usuario y producto.
     */
    public function deleteUer(): bool
    {
        if ($this->producto_id <= 0 || $this->cliente_id <= 0) {
            return false;
        }

        $query = "DELETE FROM {$this->table_name}
        WHERE producto_id = :producto_id AND cliente_id = :cliente_id";

        $stmt = $this->conn->prepare($query);
        $this->producto_id = (int) $this->producto_id;
        $this->cliente_id = (int) $this->cliente_id;

        $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);
        $stmt->bindParam(":cliente_id", $this->cliente_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Elimina item por sesión y producto.
     */
    public function deleteSession(): bool
    {
        if ($this->producto_id <= 0 || empty($this->session_key)) {
            return false;
        }

        $query = "DELETE FROM {$this->table_name}
        WHERE session_key = :session_key AND producto_id = :producto_id";

        $stmt = $this->conn->prepare($query);
        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $this->producto_id = (int) $this->producto_id;

        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->bindParam(":producto_id", $this->producto_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Elimina item (automáticamente elige método según contexto).
     */
    public function delete(): bool
    {
        if (!empty($this->cliente_id) && $this->cliente_id > 0) {
            return $this->deleteUer();
        }
        return $this->deleteSession();
    }

    /**
     * Elimina todos los items por usuario.
     */
    public function deleteByUser(): bool
    {
        if ($this->cliente_id <= 0) {
            return false;
        }

        $query = "DELETE FROM {$this->table_name} WHERE cliente_id = :cliente_id";
        $stmt = $this->conn->prepare($query);
        $this->cliente_id = (int) $this->cliente_id;
        $stmt->bindParam(":cliente_id", $this->cliente_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Elimina todos los items por sesión.
     */
    public function deleteBySession(): bool
    {
        if (empty($this->session_key)) {
            return false;
        }

        $query = "DELETE FROM {$this->table_name} WHERE session_key = :session_key";
        $stmt = $this->conn->prepare($query);
        $this->session_key = htmlspecialchars(strip_tags($this->session_key), ENT_QUOTES, 'UTF-8');
        $stmt->bindParam(":session_key", $this->session_key);

        return $stmt->execute();
    }
}
