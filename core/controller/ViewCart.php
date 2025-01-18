<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class ViewCart {

    private $table_name = 'canasta_articulos';
    private $session_key = null;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function count($session) {
        $this->session_key = $session;
        $query = "SELECT count(*) FROM " . $this->table_name . " WHERE session_key=:session_key";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":session_key", $this->session_key);
        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    public function read($session) {
        $this->session_key = $session;
        $query = "SELECT p.idPrd, p.producto, p.precio, ci.cantidad, ci.cantidad * p.precio AS subtotal
                  FROM " . $this->table_name . " ci
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

}




