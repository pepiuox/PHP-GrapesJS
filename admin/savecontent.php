<?php

include '../config/dbconnection.php';

if (isset($_POST['title'])) {
	$title = $_POST['title'];
	$content = $_POST['content'];
	$sql = "INSERT INTO page (title, content) VALUES ('" . protect($title) . "', '" . protect($content) . "')";
	if ($conn->query($sql) === TRUE) {
		$last_id = $conn->insert_id;
		$sqlm = "INSERT INTO menu (page_id, title_page) VALUES ('" . $last_id . "', '" . protect($title) . "')";
		if ($conn->query($sqlm) === TRUE) {
			echo "Page " . $title . " : Created ";
		} else {
			echo "Failed";
		}
		$_SESSION["title"] = $title;
		$_SESSION["page"] = $last_id;
	} else {
		echo "Failed";
	}
}
?>
