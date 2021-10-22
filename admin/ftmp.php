<?php
if(isset($_POST['addrow'])){
$theme_id = $_POST['theme_id'];
 $body = $_POST['body'];
 $text = $_POST['text'];
 $links = $_POST['links'];

$sql = "INSERT INTO theme_base_colors (theme_id, body, text, links)
VALUES ('$theme_id', '$body', '$text', '$links')";
if ($this->connection->query($sql) === TRUE) {
    $_SESSION['success'] = 'The data was added correctly';
header('Location: dashboard.php?cms=crud&w=list&tbl=theme_base_colors');
} else {
    $_SESSION['error'] = 'Error: ' . $this->connection->error;
}

$this->connection->close();
}?> 
