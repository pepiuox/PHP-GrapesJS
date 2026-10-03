<?php
//
//  This application develop by PEPIUOX.
//  Already using PDO - Minor cleanup applied
//
class Offers {
    protected $conn;
    public $sth;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function productOffers(string $name, string $colour): string {
        return "{$name}: {$colour}";
    }

    /**
     * Ejemplo de uso con PDO::FETCH_FUNC (ya estaba bien)
     * $result = $this->conn->prepare("SELECT name, colour FROM fruit");
     * $result->execute();
     * $rows = $result->fetchAll(PDO::FETCH_FUNC, [$this, 'productOffers']);
     */
}
