<?php
if(isset($_POST['addrow'])){
$user_id = $_POST['user_id'];
 $nombre = $_POST['nombre'];
 $edad = $_POST['edad'];
 $tipo_figura = $_POST['tipo_figura'];
 $estatura = $_POST['estatura'];
 $busto = $_POST['busto'];
 $cintura = $_POST['cintura'];
 $caderas = $_POST['caderas'];
 $detalles_fisicos = $_POST['detalles_fisicos'];
 $zonas = $_POST['zonas'];
 $citas = $_POST['citas'];
 $salidas = $_POST['salidas'];
 $dias = $_POST['dias'];
 $horarios = $_POST['horarios'];
 $descripcion_servicio = $_POST['descripcion_servicio'];
 $servicios = $_POST['servicios'];
 $adicionales = $_POST['adicionales'];
 $pack_videos = $_POST['pack_videos'];
 $otras_atenciones = $_POST['otras_atenciones'];

$sql = "INSERT INTO datos_personales (user_id, nombre, edad, tipo_figura, estatura, busto, cintura, caderas, detalles_fisicos, zonas, citas, salidas, dias, horarios, descripcion_servicio, servicios, adicionales, pack_videos, otras_atenciones)
VALUES ('$user_id', '$nombre', '$edad', '$tipo_figura', '$estatura', '$busto', '$cintura', '$caderas', '$detalles_fisicos', '$zonas', '$citas', '$salidas', '$dias', '$horarios', '$descripcion_servicio', '$servicios', '$adicionales', '$pack_videos', '$otras_atenciones')";
if ($this->connection->query($sql) === TRUE) {
    $_SESSION['success'] = 'The data was added correctly';
header('Location: dashboard.php?cms=crud&w=list&tbl=datos_personales');
} else {
    $_SESSION['error'] = 'Error: ' . $this->connection->error;
}

$this->connection->close();
}?> 
