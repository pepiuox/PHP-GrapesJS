
<?php
include "db.php";
$res = array("error" => false);

$action = "read";

if (isset($_GET["action"])) {
	$action = $_GET["action"];
}

if ($action == "read") {$result = $conn->query("SELECT * FROM menu LEFT JOIN page ON menu.page_id = page.id");
		$datos = array();
			while ($row = $result->fetch_assoc()) {
				array_push($datos, $row);
			}

			$res["datos"] = $datos;


}
// Create form

if ($action == "create") {

	$sort= $_POST['sort'];
 $page_id= $_POST['page_id'];
 $title_page= $_POST['title_page'];
 $link_page= $_POST['link_page'];
 $parent_id= $_POST['parent_id'];


	$result = $conn->query("INSERT INTO menu(sort , page_id , title_page , link_page , parent_id) VALUES ('$sort' , '$page_id' , '$title_page' , '$link_page' , '$parent_id')");

	if ($result) {
		$res["message"] = "dato agregado exitosamente";
	} else {
		$res["error"] = true;
		$res["message"] = "dato no se agrego exitosamente";
	}

	// $res["datos"] =$datos;
}
// end of create form
// update form

if ($action == "update") {
	$idMenu= $_POST['idMenu'];
 $sort= $_POST['sort'];
 $page_id= $_POST['page_id'];
 $title_page= $_POST['title_page'];
 $link_page= $_POST['link_page'];
 $parent_id= $_POST['parent_id'];


	$result = $conn->query("UPDATE menu SET sort ='$sort' , page_id ='$page_id' , title_page ='$title_page' , link_page ='$link_page' , parent_id ='$parent_id' WHERE idMenu ='$idMenu' ");

	if ($result) {
		$res["message"] = "dato actualizado con éxito";
	} else {
		$res["error"] = true;
		$res["error"] = "dato no se actualizo con éxito";
	}
}

// end of update form

if ($action == "delete") {
	$idMenu = $_POST['idMenu'];

	$result = $conn->query("DELETE FROM `menu` WHERE idMenu ='$idMenu' ");

	if ($result) {
		$res["message"] = "dato borrado exitosamente";
	} else {
		$res["error"] = true;
		$res["message"] = "dato no se borro exitosamente";
	}
	// $res["datos"] =$datos;
}

$conn->close();
header("content-type:application/json");
echo json_encode($res);
die();
?>
