<?php

/**
 * Description of Offers
 *
 * @author Lab-eMotion
 */
class Offers {

    protected $conn;
    public $sth;

//constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    public function productOffers($name, $colour) {
        return "{$name}: {$colour}";
    }

    /*
      $result = $this->conn->prepare("SELECT name, colour FROM fruit");
      $result->execute();

      $result = $sth->fetchAll(PDO::FETCH_FUNC, "fruit");
     * */
}

