<?php
include_once('conn.php');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport" content="width-device=width,initial-scale=1" />
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<title>PHP CRUD</title>
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script src="js/popper.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<?php
		$sql = "SELECT * FROM table_queries WHERE name_table='product_category'";
		$result = $link->query($sql);
		$rowcq = mysqli_num_rows($result);
		$r = 0;

		if ($r < $rowcq) {
			$rqu = $result->fetch_assoc();
			$mn = $rqu['j_value'];
			$ver = '';
			if ($rqu['type'] != '' || $rqu['query'] != '') {
				$query = "SELECT * FROM " . $rqu['name_table'] . " " . $rqu['joins'] . " (SELECT " . $rqu['j_id'] . "," . $rqu['j_value'] . " FROM " . $rqu['j_table'] . " )" . $rqu['j_table'] . " ON " . $rqu['name_table'] . "." . $rqu['col_name'] . "=" . $rqu['j_table'] . "." . $rqu['j_id'];

				$ver .= "SELECT * FROM " . $rqu['name_table'] . " " . $rqu['joins'] . " (SELECT " . $rqu['j_id'] . "," . $rqu['j_value'] . " FROM " . $rqu['j_table'] . " )" . $rqu['j_table'] . " ON " . $rqu['name_table'] . "." . $rqu['col_name'] . "=" . $rqu['j_table'] . "." . $rqu['j_id'];
			}
			echo $ver;
			$result2 = $link->query($query);
			while ($vw = $result2->fetch_assoc()) {
				echo $vw[$mn] . " - " . $vw['category'] . "\n";
			}
		} else {
			echo 'hello';
		}
		?>
	</body>
</html>