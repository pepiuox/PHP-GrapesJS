<?php
if(isset($_POST['addrow'])){
$sort = $_POST['sort'];
 $page_id = $_POST['page_id'];
 $title_page = $_POST['title_page'];
 $link_page = $_POST['link_page'];
 $parent_id = $_POST['parent_id'];

$sql = "INSERT INTO menu (sort, page_id, title_page, link_page, parent_id)
VALUES ('$sort', '$page_id', '$title_page', '$link_page', '$parent_id')";
if ($this->connection->query($sql) === TRUE) {
    $_SESSION['success'] = 'The data was added correctly';
header('Location: dashboard.php?cms=crud&w=list&tbl=menu');
} else {
    $_SESSION['error'] = 'Error: ' . $this->connection->error;
}

$this->connection->close();
}?> 
