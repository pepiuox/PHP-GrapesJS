<?php
if(isset($_POST['addrow'])){
$table_name = $_POST['table_name'];
 $col_name = $_POST['col_name'];
 $type_input = $_POST['type_input'];
 $list_page = $_POST['list_page'];
 $add_page = $_POST['add_page'];
 $update_page = $_POST['update_page'];
 $view_page = $_POST['view_page'];
 $delete_page = $_POST['delete_page'];
 $search_text = $_POST['search_text'];
 $col_set = $_POST['col_set'];

$sql = "INSERT INTO cols_set (table_name, col_name, type_input, list_page, add_page, update_page, view_page, delete_page, search_text, col_set)
VALUES ('$table_name', '$col_name', '$type_input', '$list_page', '$add_page', '$update_page', '$view_page', '$delete_page', '$search_text', '$col_set')";
if ($this->connection->query($sql) === TRUE) {
    echo 'Se agrego el dato correctamente';
header('Location: dashboard.php?cms=crud&w=list&tbl=cols_set');
} else {
    echo 'Error: ' . $this->connection->error;
}

$this->connection->close();
}?> 
