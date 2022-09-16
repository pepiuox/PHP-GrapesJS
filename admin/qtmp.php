<?php
//This is temporal file only for add new row
if(isset($_POST['addtable'])){
$result = $conn->query("SELECT name_table FROM table_queries WHERE name_table = 'configuration'");
if ($result->num_rows > 0) {
echo 'This table already exists, It was already added.';
}else{
$query = "INSERT INTO table_queries (name_table, col_name, col_type) VALUES
('configuration', 'config_name', 'varchar'), 
('configuration', 'config_value', 'varchar')";
if ($conn->query($query) === TRUE) {
            echo "Record added successfully";
        } else {
            echo "Error added record: " . $conn->error;
        }
    }
}
?>