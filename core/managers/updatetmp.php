<?php
//This is temporal file only for add new row
//
// ✅ CORRECCIÓN de documentación y robustez: `$id` NO es una variable indefinida.
// Este archivo es un *include* y lo aporta su único incluirdor,
// `core/managers/views/crud.php` (línea 128: `$id = $_GET["id"];`).
// Aun así, se resuelve de forma explícita para que el archivo funcione en cualquier
// contexto y no dependa de dónde se incluya.
$rowId = $id ?? ($_GET['id'] ?? null);

if (isset($_POST['editrow']) && $rowId !== null) {
    $sort = $_POST['sort'];
    $page_id = $_POST['page_id'];
    $title_page = $_POST['title_page'];
    $link_page = $_POST['link_page'];
    $parent_id = $_POST['parent_id'];

    $query = "UPDATE `menu` SET sort = ?, page_id = ?, title_page = ?, link_page = ?, parent_id = ? WHERE idMenu = ? ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$sort, $page_id, $title_page, $link_page, $parent_id, $rowId]);
    $stmt->closeCursor();
    $_SESSION["success"] = "The data was updated correctly.";
    echo "<script>
window.onload = function() {
    location.href = 'dashboard/table_crud/list&tbl=menu';
}
</script>";
}
?>
