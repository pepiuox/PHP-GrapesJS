<?php

include 'config/dbconnection.php';

if (isset($_POST['id'])) {
	$id = $_POST['id'];
	$title = $_POST['title'];
	$content = $_POST['content'];
	$sql = "UPDATE page SET title='" . protect($title) . "', content='" . protect($content) . "' WHERE id='$id'";
	if ($conn->query($sql) === TRUE) {
		echo "Page : Updated";
	} else {
		echo "Failed";
	}
}
?>
