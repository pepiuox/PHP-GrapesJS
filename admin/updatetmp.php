<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$sort = $_POST["sort"]; 
$page_id = $_POST["page_id"]; 
$title_page = $_POST["title_page"]; 
$link_page = $_POST["link_page"]; 
$parent_id = $_POST["parent_id"]; 

$query="UPDATE `menu` SET sort = '$sort', page_id = '$page_id', title_page = '$title_page', link_page = '$link_page', parent_id = '$parent_id' WHERE idMenu='$id' ";
if ($conn->query($query) === TRUE) {
 $_SESSION["success"] = "The data was updated correctly.";
            
echo "<script>
window.onload = function() {
    location.href = 'dashboard.php?cms=crud&w=list&tbl=menu';
}
</script>";
 } else {
              $_SESSION["error"] = "Error updating data: " . $conn->error;
            }
} 
?> 
