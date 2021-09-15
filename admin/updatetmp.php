<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$plugins = $_POST["plugins"]; 
$pluginsOpts = $_POST["pluginsOpts"]; 
$script = $_POST["script"]; 
$css = $_POST["css"]; 
$buttons = $_POST["buttons"]; 
$plugins_script = $_POST["plugins_script"]; 
$plugins_css = $_POST["plugins_css"]; 

        $query="UPDATE `$tble` SET plugins = '$plugins', pluginsOpts = '$pluginsOpts', script = '$script', css = '$css', buttons = '$buttons', plugins_script = '$plugins_script', plugins_css = '$plugins_css' WHERE id=$id ";
if ($this->connection->query($query) === TRUE) {
               echo "Los datos fueron actualizados correctamente.";
               header("Location: dashboard.php?cms=crud&w=list&tbl=plugins_app");
            } else {
               echo "Error en actualizar datos: " . $this->connection->error;
            }
    } 
?> 
