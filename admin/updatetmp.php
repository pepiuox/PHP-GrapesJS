<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$container = $_POST["container"]; 
$spacer = $_POST["spacer"]; 
$radius = $_POST["radius"]; 
$radius_sm = $_POST["radius_sm"]; 
$radius_lg = $_POST["radius_lg"]; 
$font_size = $_POST["font_size"]; 

$query="UPDATE `theme_settings` SET container = '$container', spacer = '$spacer', radius = '$radius', radius_sm = '$radius_sm', radius_lg = '$radius_lg', font_size = '$font_size' WHERE idts='$id' ";
if ($conn->query($query) === TRUE) {
 $_SESSION["success"] = "The data was updated correctly.";
            
echo "<script>
window.onload = function() {
    location.href = 'dashboard.php?cms=table_crud&w=list&tbl=theme_settings';
}
</script>";
 } else {
              $_SESSION["error"] = "Error updating data: " . $conn->error;
            }
} 
?> 
