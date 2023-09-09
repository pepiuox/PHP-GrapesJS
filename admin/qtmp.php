<?php
//This is temporal file only for add new row
if(isset($_POST['addtable'])){
$result = $conn->query("SELECT table_name FROM table_column_settings WHERE table_name = 'page'");
if ($result->num_rows > 0) {
echo 'This table already exists, It was already added.';
}else{
$query = "INSERT INTO table_column_settings (table_name, col_name, col_type) VALUES
('page', 'language', 'int'), 
('page', 'position', 'int'), 
('page', 'title', 'varchar'), 
('page', 'link', 'varchar'), 
('page', 'url', 'varchar'), 
('page', 'keyword', 'varchar'), 
('page', 'classification', 'varchar'), 
('page', 'description', 'varchar'), 
('page', 'image', 'varchar'), 
('page', 'type', 'enum'), 
('page', 'menu', 'int'), 
('page', 'hidden_page', 'tinyint'), 
('page', 'path_file', 'varchar'), 
('page', 'script_name', 'varchar'), 
('page', 'template', 'varchar'), 
('page', 'base_template', 'varchar'), 
('page', 'content', 'longtext'), 
('page', 'style', 'longtext'), 
('page', 'startpage', 'int'), 
('page', 'level', 'int'), 
('page', 'parent', 'int'), 
('page', 'sort', 'int'), 
('page', 'active', 'int'), 
('page', 'update', 'timestamp')";
if ($conn->query($query) === TRUE) {
$ins_qry = "INSERT INTO table_settings(table_name) VALUES('page')";
if ($conn->query($ins_qry) === TRUE){
echo "Record added successfully";
echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=editor&tbl=page">';
} else {
echo "Error added record: " . $conn->error;
}
} else {
echo "Error added record: " . $conn->error;
}
}
}
?>