<?php
//This is temporal file only for add new row
if(isset($_POST['addtable'])){
$result = $conn->query("SELECT table_name FROM table_column_settings WHERE table_name = 'templates'");
if ($result->num_rows > 0) {
echo 'This table already exists, It was already added.';
}else{
$query = "INSERT INTO table_column_settings (table_name, col_name, col_type) VALUES
('templates', 'templates', 'varchar')";
if ($conn->query($query) === TRUE) {
$ins_qry = "INSERT INTO table_settings(table_name) VALUES('templates')";
if ($conn->query($ins_qry) === TRUE){
echo "Record added successfully";
echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=editor&tbl=templates">';
} else {
echo "Error added record: " . $conn->error;
}
} else {
echo "Error added record: " . $conn->error;
}
}
}
?>