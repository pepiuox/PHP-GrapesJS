<?php

//This is temporal file only for add new row
if (isset($_POST['editrow'])) {
    $nombre_granja = $_POST["nombre_granja"];
    $numero_galpones = $_POST["numero_galpones"];
    $empresa_id = $_POST["empresa_id"];
    $empresa_integrada = $_POST["empresa_integrada"];
    $total_personal = $_POST["total_personal"];
    $en_planilla = $_POST["en_planilla"];

    $query = "UPDATE `$tble` SET nombre_granja = '$nombre_granja', numero_galpones = '$numero_galpones', empresa_id = '$empresa_id', empresa_integrada = '$empresa_integrada', total_personal = '$total_personal', en_planilla = '$en_planilla' WHERE idGrnj=$id ";
    if ($conn->query($query) === TRUE) {
        echo "Los datos fueron actualizados correctamente.";
        header("Location: index.php?w=list&tbl=granjas");
    } else {
        echo "Error en actualizar datos: " . $conn->error;
    }
}
?> 
