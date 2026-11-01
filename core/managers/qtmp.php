<?php

//This is temporal file only for add new row
if (isset($_POST['addtable'])) {
    $result = $conn->query("SELECT table_name FROM table_column_settings WHERE table_name = 'templates'");
    if ($result->rowCount() > 0) {
        echo 'This table already exists, It was already added.';
    } else {
        $query = "INSERT INTO table_column_settings (table_name, col_name, col_type) VALUES
('templates', 'templates', 'varchar')";
        // ✅ PDO::query() devuelve un PDOStatement (nunca TRUE) y lanza PDOException si falla.
        if ($conn->query($query) !== false) {
            $ins_qry = "INSERT INTO table_settings(table_name) VALUES('templates')";
            if ($conn->query($ins_qry) !== false) {
                echo "Record added successfully";
                echo '<meta http-equiv="refresh" content="0;url=dashboard/table_manager/editor/templates">';
            } else {
                echo "Error added record: " . $conn->errorInfo()[2];
            }
        } else {
            echo "Error added record: " . $conn->errorInfo()[2];
        }
    }
}
?>