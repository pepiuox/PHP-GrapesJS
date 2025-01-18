<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class OrderCart {

    protected $conn;
    private $table_name = 'orden';
    private $table_items = 'canasta_articulos';
    public $session_key;
    public $orden_id;
    public $producto_id;
    public $precio_articulo;
    public $cantidad;
    public $monto_articulos;
    private $cliente_id;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    public function idOrder($len = 16) {
        $bytes = random_bytes(16);
        return bin2hex($bytes) . substr(sha1(openssl_random_pseudo_bytes(21)), - $len);
    }

    public function orderItems() {
        $query = "SELECT * FROM " . $this->table_items . " ca LEFT JOIN productos p ON ca.producto_id = p.idPrd WHERE session_key=:session_key";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        // bind value
        $stmt->bindParam(":session_key", $this->session_key);
        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    public function checkOrder() {
        $query = "SELECT * FROM orden WHERE session_id=:session_id";
        $result = $this->conn->prepare($query);
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $result->bindParam(":session_id", $this->session_key);
        // execute query
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateOrder(){
        // query to insert cart item record
        $query = "UPDATE " . $this->table_name . "
                  SET cantidad=:cantidad
                  WHERE session_key=:session_key AND producto_id=:producto_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));

        // bind values
        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    public function order($orden_id, $monto_total) {

        // query to insert cart item record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET       
                session_id = :session_id,
                    orden_id = :orden_id,
                    cliente_id = :cliente_id,
                    monto_total = :monto_total";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $this->orden_id = htmlspecialchars(strip_tags($orden_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->monto_total = htmlspecialchars(strip_tags($monto_total));

        // bind values
        $stmt->bindParam(":session_id", $this->session_key);
        $stmt->bindParam(":orden_id", $this->orden_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":monto_total", $this->monto_total);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function purchaseOrder($orden_id, $monto_total) {

        // query to insert cart item record
        $query = "INSERT INTO
                    orden_compra
                SET       
                    session_id = :session_id,
                    orden_id = :orden_id,
                    cliente_id = :cliente_id,
                    monto_total = :monto_total";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $this->orden_id = htmlspecialchars(strip_tags($orden_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->monto_total = htmlspecialchars(strip_tags($monto_total));

        // bind values
        $stmt->bindParam(":session_id", $this->session_key);
        $stmt->bindParam(":orden_id", $this->orden_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":monto_total", $this->monto_total);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function saleOrder($orden_id, $producto_id, $precio_articulo, $cantidad, $monto_articulos) {

        // query to insert cart item record
        $query = "INSERT INTO
                    orden_articulos
                SET       
                    orden_id = :orden_id,
                    producto_id = :producto_id,
                    precio_articulo = :precio_articulo,
                    cantidad = :cantidad,
                    monto_articulos = :monto_articulos,
                    cliente_id = :cliente_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize 
        $this->orden_id = htmlspecialchars(strip_tags($orden_id));
        $this->producto_id = htmlspecialchars(strip_tags($producto_id));
        $this->precio_articulo = htmlspecialchars(strip_tags($precio_articulo));
        $this->cantidad = htmlspecialchars(strip_tags($cantidad));
        $this->monto_articulos = htmlspecialchars(strip_tags($monto_articulos));

        // bind values
        $stmt->bindParam(":orden_id", $this->orden_id);
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":precio_articulo", $this->precio_articulo);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":monto_articulos", $this->monto_articulos);
        $stmt->bindParam(":cliente_id", $this->cliente_id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function process() {
        $query = "SELECT * FROM orden_compra WHERE session_id=:session_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        // bind value
        $stmt->bindParam(":session_id", $this->session_key);
        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

}
