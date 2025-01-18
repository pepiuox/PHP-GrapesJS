<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
// A cart item object
class AddCart
{
    // database connection and table name
    protected $conn;
    private $table_name = "canasta_articulos";
    //object properties
    public $idPrd;
    public $idCnt;
    public $session_key;
    public $producto_id;
    public $cantidad;
    public $cliente_id;
    public $creado;
    public $modificado;

    //constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function userconnect($clientSession)
    {
        $this->cliente_id = $clientSession;
    }

    // get images product
    public function imagenProducto()
    {
        $query =
            "SELECT * FROM imagenes_productos WHERE producto_id =:producto_id";
        $result = $this->conn->prepare($query);
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $result->bindParam(":producto_id", $producto_id);
        // execute query
        $result->execute();
        return $result;
    }

    // get all data from one product
    public function product()
    {
        $query = "SELECT * FROM productos WHERE idPrd=:idPrd";
        $result = $this->conn->prepare($query);
        $this->idPrd = htmlspecialchars(strip_tags($this->idPrd));
        $result->bindParam(":idPrd", $this->idPrd);
        // execute query
        $result->execute();
        return $result;
    }

    // Select one single product with rational data
    public function singleProduct()
    {
        $query =
            "SELECT * FROM productos p " .
            "LEFT JOIN marcas m ON p.marca_id = m.idMarc " .
            "LEFT JOIN categorias c ON p.categoria_id = c.idCat " .
            "LEFT JOIN sub_categorias sc ON p.sub_categoria_id = sc.idSubc " .
            "LEFT JOIN familias_productos fp ON p.familia_id = fp.idFam " .
            "LEFT JOIN descuentos_productos dp ON p.idPrd = dp.producto_id WHERE idPrd=:idPrd";
        $result = $this->conn->prepare($query);
        $this->idPrd = htmlspecialchars(strip_tags($this->idPrd));
        $result->bindParam(":idPrd", $this->idPrd);
        // execute query
        $result->execute();
        return $result;
    }

    public function ratingProducto()
    {
        $query = "SELECT * FROM rating_producto WHERE producto_id=:producto_id";
        $result = $this->conn->prepare($query);
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $result->bindParam(":producto_id", $this->producto_id);
        // execute query
        $result->execute();
        return $result;
    }
    public function ratingServicio()
    {
        $query = "SELECT * FROM rating_producto WHERE servicio_id=:servicio_id";
        $result = $this->conn->prepare($query);
        $this->servicio_id = htmlspecialchars(strip_tags($this->servicio_id));
        $result->bindParam(":servicio_id", $this->servicio_id);
        // execute query
        $result->execute();
        return $result;
    }

    // get all data from one product
    public function itemsProduct()
    {
        $query =
            "SELECT * FROM canasta_articulos WHERE session_key=:session_key";
        $stmt = $this->conn->prepare($query);
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $stmt->bindParam(":session_key", $this->session_key);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    public function itemsCart()
    {
        $sscart = $_SESSION["client_session"];

        $cart = $this->conn->prepare(
            "SELECT COUNT(*) AS num FROM canasta_articulos WHERE session_key=:session_key"
        );
        $cart->bindParam(":session_key", $sscart);
        $cart->execute();
        $row = $cart->fetch(PDO::FETCH_ASSOC);

        echo '<a type="button" class="btn btn-outline-heart" name="cartplus" href="' .
            PATH_APP .
            'cart">';
        if ($row["num"] > 0) {
            echo '<i class="fas fa-cart-plus"></i> ' . $row["num"];
        } else {
            echo '<i class="fas fa-shopping-cart"></i>';
        }
        echo "</a>";
    }

    // check if a cart item exists
    public function exists()
    {
        // query to count existing cart item
        $query =
            "SELECT count(*) FROM " .
            $this->table_name .
            " WHERE producto_id=:producto_id AND session_key=:session_key";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        // bind category id variable
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":session_key", $this->session_key);
        // execute query
        $stmt->execute();
        // get row value
        $rows = $stmt->fetch(PDO::FETCH_NUM);
        // return
        if ($rows[0] > 0) {
            return true;
        }
        return false;
    }

    //count user's items in the cart
    public function count()
    {
        //query to count existing user's cart items
        $query =
            "SELECT count(*) FROM " .
            $this->table_name .
            " WHERE session_key=:session_key";
        //prepare the query statement
        $stmt = $this->conn->prepare($query);
        //sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        //bind category id variable
        $stmt->bindParam(":session_key", $this->session_key);
        //execute query
        $stmt->execute();
        //get row value
        $rows = $stmt->fetch(PDO::FETCH_NUM);
        return $rows[0];
    }

    // quantity
    public function quantity()
    {
        $quantity = $this->conn->prepare(
            "SELECT * FROM canasta_articulos WHERE session_key=:session_key AND producto_id=:producto_id"
        );
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $quantity->bindParam(":session_key", $this->session_key);
        $quantity->bindParam(":producto_id", $this->producto_id);
        // execute query
        $quantity->execute();
        return $quantity;
    }

    // create cart item record
    public function createUser()
    {
        // to get times-tamp for 'creado' field
        $this->creado = date("Y-m-d H:i:s");

        // query to insert cart item record
        $query =
            "INSERT INTO
                    " .
            $this->table_name .
            "
                SET
                    session_key = :session_key,
                    producto_id = :producto_id,
                    cantidad = :cantidad,
                    cliente_id = :cliente_id,
                    creado = :creado";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));

        // bind values
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":creado", $this->creado);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function createSession()
    {
        // to get times-tamp for 'creado' field
        $this->creado = date("Y-m-d H:i:s");

        // query to insert cart item record
        $query =
            "INSERT INTO
                    " .
            $this->table_name .
            "
                SET
                    session_key = :session_key,
                    producto_id = :producto_id,
                    cantidad = :cantidad,
                    creado = :creado";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));

        // bind values
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":creado", $this->creado);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // read items in the cart
    public function read()
    {
        $query =
            "SELECT p.idPrd, p.producto, p.precio, ci.cantidad, ci.cantidad * p.precio AS subtotal
                  FROM " .
            $this->table_name .
            " ci
                      LEFT JOIN productos p
                          ON ci.producto_id = p.idPrd
                  WHERE ci.session_key=:session_key";

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

    // create cart item record
    function update()
    {
        // query to insert cart item record
        $query =
            "UPDATE " .
            $this->table_name .
            "
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

    // remove cart item by user and product
    public function deleteUer()
    {
        // delete query
        $query =
            "DELETE FROM " .
            $this->table_name .
            " WHERE producto_id=:producto_id AND cliente_id=:cliente_id";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));

        // bind ids
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // remove cart item by user and product
    public function deleteSession()
    {
        // delete query
        $query =
            "DELETE FROM " .
            $this->table_name .
            " WHERE session_key=:session_key AND producto_id=:producto_id";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));

        // bind ids
        $stmt->bindParam(":session_key", $this->session_key);
        $stmt->bindParam(":producto_id", $this->producto_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        if (!empty($this->cliente_id)) {
            $this->deleteUer();
        } else {
            $this->deleteSession();
        }
    }

    // remove cart items by user
    public function deleteByUser()
    {
        // delete query
        $query =
            "DELETE FROM " .
            $this->table_name .
            " WHERE cliente_id=:cliente_id";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));

        // bind id
        $stmt->bindParam(":cliente_id", $this->cliente_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // remove cart items by user
    public function deleteBySession()
    {
        // delete query
        $query =
            "DELETE FROM " .
            $this->table_name .
            " WHERE session_key=:session_key";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));

        // bind id
        $stmt->bindParam(":session_key", $this->session_key);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}

?>
