<?php

/* Update Page */
include '../config/dbconnection.php';

if (isset($_POST['content'])) {
	$idp = $_POST['idp'];
	$content = $_POST['content'];
	$style = $_POST['style'];

	$sql = "UPDATE page SET  content='" . protect($content) . "', style='" . protect($style) . "' WHERE id='$idp'";
	if ($conn->query($sql) === TRUE) {
		echo "The page has been updated";
	} else {
		echo "Failed";
	}
}
?>
