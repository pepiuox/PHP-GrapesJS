<?php
//This is temporal file only for add new row
if(isset($_POST['addtable'])){
$result = $conn->query("SELECT name_table FROM table_queries WHERE name_table = 'cols_set'");
if ($result->num_rows > 0) {
echo 'This table already exists, It was already added.';
}else{
$query = "INSERT INTO table_queries (name_table, col_name, col_type) VALUES
('cols_set', 'table_name', 'varchar'), 
('cols_set', 'col_name', 'varchar'), 
('cols_set', 'type_input', 'varchar'), 
('cols_set', 'list_page', 'varchar'), 
('cols_set', 'add_page', 'varchar'), 
('cols_set', 'update_page', 'varchar'), 
('cols_set', 'view_page', 'varchar'), 
('cols_set', 'delete_page', 'varchar'), 
('cols_set', 'search_text', 'varchar'), 
('cols_set', 'col_set', 'varchar')";
if ($conn->query($query) === TRUE) {
            echo "Record added successfully";
        } else {
            echo "Error added record: " . $conn->error;
        }
    }
}
?>