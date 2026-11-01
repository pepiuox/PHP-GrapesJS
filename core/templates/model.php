<?php

Class Model {

    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'vuejs_crud_php';
    private ?PDO $pdo; //create connection 

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Exception $e) {
            echo "Connection error " . $e->getMessage();
        }
    }

    // insert book record into database 
    public function insert($name, $author) {
        $query = "INSERT INTO tbl_book (book_name, book_author) VALUES (:name, :author)";
        $sql = $this->pdo->prepare($query);
        if ($sql->execute([':name' => $name, ':author' => $author])) {
            return true;
        } else {
            return;
        }
    }

    //fetch all book record from database 
    public function fetchAll() {
        $data = [];
        $query = "SELECT * FROM tbl_book";
        if ($sql = $this->pdo->query($query)) {
            while ($rows = $sql->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $rows;
            }
        } return $data;
    }

    //delete book record from database 
    public function delete($id) {
        $query = "DELETE FROM tbl_book WHERE book_id = :id ";
        $sql = $this->pdo->prepare($query);
        if ($sql->execute([':id' => $id])) {
            return true;
        } else {
            return;
        }
    }

    //fetch single book record from database 
    public function edit($id) {
        $data = [];
        $query = "SELECT * FROM tbl_book WHERE book_id = :id ";
        $sql = $this->pdo->prepare($query);
        if ($sql->execute([':id' => $id])) {
            $row = $sql->fetch(PDO::FETCH_NUM);
            $data = $row;
        } return $data;
    }

    //update book record 
    public function update($id, $name, $author) {
        $query = "UPDATE tbl_book SET book_name = :name, book_author = :author WHERE book_id = :id ";
        $sql = $this->pdo->prepare($query);
        if ($sql->execute([':name' => $name, ':author' => $author, ':id' => $id])) {
            return true;
        } else {
            return;
        }
    }
}

?>