<?php 
Class Model {
     private $host = 'localhost'; 
     private $username = 'root'; 
     private $password = ''; 
     private $dbname = 'vuejs_crud_php'; 
     private $connection; //create connection 
     public function __construct() { 
        try { $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname); }
         catch (Exception $e) {
             echo "Connection error " . $e->getMessage(); 
            } 
        } 
     // insert book record into database 
     public function insert($name, $author) {
         $query = "INSERT INTO tbl_book (book_name, book_author) VALUES ('".$name."', '".$author."')"; 
         if ($sql = $this->connection->query($query)) { return true; } else { return; } } 
     //fetch all book record from database 
     public function fetchAll() { 
        $data = []; $query = "SELECT * FROM tbl_book"; 
        if ($sql = $this->connection->query($query)) { while ($rows = mysqli_fetch_assoc($sql)) { $data[] = $rows; } } return $data; } 
     //delete book record from database 
     public function delete($id) {
         $query = "DELETE FROM tbl_book WHERE book_id = '".$id."' "; 
         if ($sql = $this->connection->query($query)) { return true; } else { return; } } 
     //fetch single book record from database 
     public function edit($id) { 
        $data = []; $query = "SELECT * FROM tbl_book WHERE book_id = '".$id."' "; 
        if ($sql = $this->connection->query($query)) { $row = mysqli_fetch_row($sql); $data = $row; } return $data; } 
     //update book record 
     public function update($id, $name, $author) { 
        $query = "UPDATE tbl_book SET book_name = '".$name."', book_author = '".$author."' WHERE book_id = '".$id."' "; if ($sql = $this->connection->query($query)) { return true; } else { return; } 
    } 
    } 
     ?>