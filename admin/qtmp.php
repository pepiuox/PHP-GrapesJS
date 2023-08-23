<?php
//This is temporal file only for add new row
if(isset($_POST['addtable'])){
$result = $conn->query("SELECT name_table FROM table_queries WHERE name_table = 'menu'");
if ($result->num_rows > 0) {
echo 'This table already exists, It was already added.';
}else{
$query = "INSERT INTO table_queries (name_table, col_name, col_type) VALUES
('menu', 'sort', 'int'), 
('menu', 'page_id', 'int'), 
('menu', 'title_page', 'varchar'), 
('menu', 'link_page', 'varchar'), 
('menu', 'parent_id', 'int')";
if ($conn->query($query) === TRUE) {
 echo "Record added successfully";
 echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=menu">';
} else {
   echo "Error added record: " . $conn->error;
   }
}
}
?>