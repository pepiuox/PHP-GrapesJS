<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$id_page = $_POST["id_page"]; 
$theme_name = $_POST["theme_name"]; 
$theme = $_POST["theme"]; 
$base_default = $_POST["base_default"]; 
$active_theme = $_POST["active_theme"]; 

$query="UPDATE `themes` SET id_page = '$id_page', theme_name = '$theme_name', theme = '$theme', base_default = '$base_default', active_theme = '$active_theme' WHERE theme_id='$id' ";
if ($conn->query($query) === TRUE) {
 $_SESSION["success"] = "The data was updated correctly.";
            
echo "<script>
window.onload = function() {
    location.href = 'dashboard.php?cms=table_crud&w=list&tbl=themes';
}
</script>";
 } else {
              $_SESSION["error"] = "Error updating data: " . $conn->error;
            }
} 
?> 
