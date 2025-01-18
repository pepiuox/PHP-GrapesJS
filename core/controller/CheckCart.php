<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
// A cart item object
class CheckCart
{
    // database connection and table name
    protected $conn;
    private $table_name = "canasta_articulos";
    //object properties
    public $idCnt;
    public $session;
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

    // check if a cart item exists
    public function exists()
    {
        // query to count existing cart item
        if (isset($_SESSION["client_id"])) {
            $query =
                "SELECT count(*) FROM " .
                $this->table_name .
                " WHERE session_key=:session_key AND producto_id=:producto_id AND cliente_id=:cliente_id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->session_key = htmlspecialchars(
                strip_tags($this->session_key)
            );
            $this->producto_id = htmlspecialchars(
                strip_tags($this->producto_id)
            );
            $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));

            // bind category id variable
            $stmt->bindParam(":session_key", $this->session_key);
            $stmt->bindParam(":producto_id", $this->producto_id);
            $stmt->bindParam(":cliente_id", $this->cliente_id);
        } else {
            $query =
                "SELECT count(*) FROM " .
                $this->table_name .
                " WHERE session_key=:session_key AND producto_id=:producto_id";
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->session_key = htmlspecialchars(
                strip_tags($this->session_key)
            );
            $this->producto_id = htmlspecialchars(
                strip_tags($this->producto_id)
            );

            // bind category id variable
            $stmt->bindParam(":session_key", $this->session_key);
            $stmt->bindParam(":producto_id", $this->producto_id);
        }

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
        if (isset($_SESSION["client_id"])) {
            $query =
                "SELECT count(*) FROM " .
                $this->table_name .
                " WHERE cliente_id=:cliente_id";
            //prepare the query statement
            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));

            //bind category id variable
            $stmt->bindParam(":cliente_id", $this->cliente_id);
        } else {
            $query =
                "SELECT count(*) FROM " .
                $this->table_name .
                " WHERE session_key=:session_key";
            //prepare the query statement
            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));

            //bind category id variable
            $stmt->bindParam(":cliente_id", $this->cliente_id);
        }

        //execute query
        $stmt->execute();

        //get row value
        $rows = $stmt->fetch(PDO::FETCH_NUM);

        return $rows[0];
    }

    // create cart item record
    function createUser()
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
                    producto_id = :producto_id,
                    cantidad = :cantidad,
                    cliente_id = :cliente_id,
                    creado = :creado";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
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
                    producto_id = :producto_id,
                    cantidad = :cantidad,
                    session_key = :session_key,
                    creado = :creado";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));

        // bind values
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":session_key", $this->session_key);
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
        $stmt->bindParam(":session_key", $this->session_key, PDO::PARAM_INT);

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
                  WHERE producto_id=:producto_id AND session_key=:session_key";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));

        // bind values
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":session_key", $this->session_key);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // create cart item record
    function updateClient()
    {
        // query to insert cart item record

        $query =
            "UPDATE " .
            $this->table_name .
            "
                  SET cantidad=:cantidad
                  WHERE producto_id=:producto_id AND session_key=:session_key";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));

        // bind values
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":session_key", $this->session_key);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // remove cart item by user and product
    public function delete()
    {
        // delete query
        $query =
            "DELETE FROM " .
            $this->table_name .
            " WHERE session_key=:session_key AND producto_id=:producto_id";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->session_key = htmlspecialchars(strip_tags($this->session_key));

        // bind ids
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":session_key", $this->session_key);

        if ($stmt->execute()) {
            return true;
        }

        return false;
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

    // remove cart items by session
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
