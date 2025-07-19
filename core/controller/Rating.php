<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
/**
 * Description of rating
 * Process for your liked product
 * @author Lab-eMotion
 */
class Rating {

    // database connection and table name
    protected $conn;
    public $cliente_id;
    public $producto_id;
    public $servicio_id;
    public $rating;

    public function __construct($db) {
        $this->conn = $db;
    }

    // verify if exists product
    public function checkProduct() {
        $query = "SELECT * FROM productos_favoritos WHERE cliente_id=:cliente_id AND producto_id =:producto_id";
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));

        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":producto_id", $this->producto_id);
        // execute query
        $stmt->execute();
               
        // return
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // verify if exists service
    public function checkService() {
        $query = "SELECT * FROM servicios_favoritos WHERE cliente_id=:cliente_id AND servicio_id =:servicio_id";
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->servicio_id = htmlspecialchars(strip_tags($this->servicio_id));

        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":servicio_id", $this->servicio_id);
        // execute query
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_NUM);
        // return
        if ($rows[0] > 0) {
            return true;
        }
        return false;
    }

    public function heartProduct() {

        // query to insert cart item record
        $query = "INSERT INTO
                    productos_favoritos
                SET
                    cliente_id = :cliente_id,
                    producto_id = :producto_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));

        // bind values
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":producto_id", $this->producto_id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function heartService() {

        // query to insert cart item record
        $query = "INSERT INTO
                   productos_favoritos
                SET
                    cliente_id = :cliente_id,
                    servicio_id = :servicio_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->servicio_id = htmlspecialchars(strip_tags($this->servicio_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));

        // bind values
        $stmt->bindParam(":servicio_id", $this->servicio_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);


        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getRatingProduct() {
        $query = "SELECT * FROM rating_producto WHERE producto_id = :producto_id";
        $get = $this->conn->prepare($query);
        // sanitize
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        // bind value
        $get->bindParam(":producto_id", $this->producto_id);
        // execute query
        $get->execute();
        return $get;
    }

    public function getRatingService() {
        $query = "SELECT * FROM rating_servicio WHERE servicio_id =:servicio_id";
        $get = $this->conn->prepare($query);
        // sanitize
        $this->servicio_id = htmlspecialchars(strip_tags($this->servicio_id));
        // bind value
        $get->bindParam(":servicio_id", $this->servicio_id);
        // execute query
        $get->execute();
        return $get;
    }

    public function ratingProduct() {
        $query = "SELECT * FROM rating_producto WHERE producto_id = :producto_id";
        $get = $this->conn->prepare($query);
        // sanitize
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        // bind value
        $get->bindParam(":producto_id", $this->producto_id);
        // execute query
        $get->execute();
        $rnum = $get->rowCount();
        if ($rnum > 0) {
            // query to insert cart item record
            $query = "INSERT INTO
                    rating_producto
                SET
                    rating = :rating,
                    producto_id = :producto_id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->rating = htmlspecialchars(strip_tags($this->rating));
            $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));

            // bind values 
            $stmt->bindParam(":rating", $this->rating);
            $stmt->bindParam(":producto_id", $this->producto_id);

            // execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }
    }

    public function ratingService() {
        $query = "SELECT * FROM rating_servicio WHERE servicio_id =:servicio_id";
        $get = $this->conn->prepare($query);
        // sanitize
        $this->servicio_id = htmlspecialchars(strip_tags($this->servicio_id));
        // bind value
        $get->bindParam(":servicio_id", $this->servicio_id);
        // execute query
        $get->execute();
        $rnum = $get->rowCount();
        if ($rnum > 0) {

            $row = $$get->fetch(PDO::FETCH_ASSOC);
            $this->rating = 1 + $row['rating'];
            // query to insert cart item record
            $query = "INSERT INTO
                    rating_servicio
                SET
                    rating = :rating,
                    servicio_id = :servicio_id";


            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize

            $this->servicio_id = htmlspecialchars(strip_tags($this->servicio_id));
            $this->rating = htmlspecialchars(strip_tags($this->rating));

            // bind values
            $stmt->bindParam(":servicio_id", $this->servicio_id);
            $stmt->bindParam(":rating", $this->rating);


            // execute query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        }
    }

    public function ratingFavoritos($term, $id) {
        $this->producto_id = null;
        $this->servicio_id = null;
        if ($term === 'producto') {
            if ($this->checkProduct() === false) {
                $this->producto_id = $id;
                $this->ratingProduct();
            }
            return true;
        }
        if ($term === 'servicio') {
            if ($this->checkService() === false) {
                $this->servicio_id = $id;
                $this->ratingService();
            }
            return true;
        }
    }

}
