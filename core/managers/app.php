
<?php

// ✅ PDO: se usa la conexión canónica global ($conn) en lugar de un db.php inexistente.
if (!isset($conn)) {
    require_once __DIR__ . '/../config/dbconnection.php';
}
$res = array("error" => false);

$action = "read";

if (isset($_GET["action"])) {
    $action = $_GET["action"];
}

if ($action == "read") {
    // ✅ La tabla es `pages` (no existe `page`) y el calificador del ON debe coincidir
    // con el nombre real de la tabla, no con el antiguo.
    $result = $conn->query("SELECT * FROM menu LEFT JOIN pages ON menu.page_id = pages.id");
    $datos = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($datos, $row);
    }

    $res["datos"] = $datos;
}
// Create form

if ($action == "create") {

    $sort = $_POST['sort'];
    $page_id = $_POST['page_id'];
    $title_page = $_POST['title_page'];
    $link_page = $_POST['link_page'];
    $parent_id = $_POST['parent_id'];

    $stmt = $conn->prepare("INSERT INTO menu(sort , page_id , title_page , link_page , parent_id) VALUES (:sort , :page_id , :title_page , :link_page , :parent_id)");
    $result = $stmt->execute([':sort' => $sort, ':page_id' => $page_id, ':title_page' => $title_page, ':link_page' => $link_page, ':parent_id' => $parent_id]);

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
    $idMenu = $_POST['idMenu'];
    $sort = $_POST['sort'];
    $page_id = $_POST['page_id'];
    $title_page = $_POST['title_page'];
    $link_page = $_POST['link_page'];
    $parent_id = $_POST['parent_id'];

    $stmt = $conn->prepare("UPDATE menu SET sort =:sort , page_id =:page_id , title_page =:title_page , link_page =:link_page , parent_id =:parent_id WHERE idMenu =:idMenu ");
    $result = $stmt->execute([':sort' => $sort, ':page_id' => $page_id, ':title_page' => $title_page, ':link_page' => $link_page, ':parent_id' => $parent_id, ':idMenu' => $idMenu]);

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

    $stmt = $conn->prepare("DELETE FROM `menu` WHERE idMenu =:idMenu ");
    $result = $stmt->execute([':idMenu' => $idMenu]);

    if ($result) {
        $res["message"] = "dato borrado exitosamente";
    } else {
        $res["error"] = true;
        $res["message"] = "dato no se borro exitosamente";
    }
    // $res["datos"] =$datos;
}

// ✅ PDO: no existe $conn->close(); la conexión es un singleton compartido.
header("content-type:application/json");
echo json_encode($res);
die();
?>
