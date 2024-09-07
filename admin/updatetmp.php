<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) {
$sort  = $_POST['sort'];
$page_id  = $_POST['page_id'];
$title_page  = $_POST['title_page'];
$link_page  = $_POST['link_page'];
$parent_id  = $_POST['parent_id'];

$query="UPDATE `menu` SET sort = ?, page_id = ?, title_page = ?, link_page = ?, parent_id = ? WHERE idMenu = ? ";
$stmt = $conn->prepare($query);
$stmt->bind_param("iissii",$sort, $page_id, $title_page, $link_page, $parent_id, $id);
$stmt->execute();
$stmt->close();
$_SESSION["success"] = "The data was updated correctly.";
echo "<script>
window.onload = function() {
	location.href = 'dashboard.php?cms=table_crud&w=list&tbl=menu';
}
</script>";
}
?>
