<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$plugins = $_POST["plugins"]; 
$plugins_opts = $_POST["plugins_opts"]; 
$script = $_POST["script"]; 
$css = $_POST["css"]; 
$buttons = $_POST["buttons"]; 
$plugins_script = $_POST["plugins_script"]; 
$plugins_css = $_POST["plugins_css"]; 
$active = $_POST["active"]; 

$query="UPDATE `plugins_app` SET plugins = '$plugins', plugins_opts = '$plugins_opts', script = '$script', css = '$css', buttons = '$buttons', plugins_script = '$plugins_script', plugins_css = '$plugins_css', active = '$active' WHERE id='$id' ";
if ($conn->query($query) === TRUE) {
            $_SESSION["success"] = "The data was updated correctly.";
            
echo "<script>
window.onload = function() {
    location.href = 'dashboard.php?cms=crud&w=list&tbl=plugins_app';
}
</script>";
 } else {
              $_SESSION["error"] = "Error updating data: " . $conn->error;
            }
} 
?> 
