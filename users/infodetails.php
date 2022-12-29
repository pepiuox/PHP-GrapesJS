<div class="container">
    <div class="row pt-2">        
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

<?php
$userid = $_SESSION['user_id'];
$hash = $_SESSION['hash'];

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
    $_SESSION['success'] = 'Los datos se guardaron correctamente';
header('Location: dashboard.php?cms=crud&w=list&tbl=datos_personales');
} else {
    $_SESSION['error'] = 'Error: ' . $this->connection->error;
}

$this->connection->close();
}?> 
<form method="post" class="form-horizontal" role="form" id="add_datos_personales" enctype="multipart/form-data">
<div class="form-group">
                       <label for="nombre">Nombre:</label>
                       <input type="text" class="form-control" id="nombre" name="nombre">
                  </div>
<div class="form-group">
                       <label for="edad">Edad:</label>
                       <input type="text" class="form-control" id="edad" name="edad">
                  </div>
<div class="form-group">
                       <label for="tipo_figura">Tipo figura:</label>
                       <select type="text" class="form-select" id="tipo_figura" name="tipo_figura" >
<option value="Delgada">Delgada</option>
<option value="Delgada pechugona">Delgada pechugona</option>
<option value="Delgada potoncita">Delgada potoncita</option>
<option value="Esbelta">Esbelta</option>
<option value="Esbelta pechugona">Esbelta pechugona</option>
<option value="Esbelta potoncita">Esbelta potoncita</option>
<option value="Curvilineo">Curvilineo</option>
<option value="Llenita">Llenita</option>
</select>
</div>
<div class="form-group">
                       <label for="estatura">Estatura:</label>
                       <input type="text" class="form-control" id="estatura" name="estatura">
                  </div>
<div class="form-group">
                       <label for="busto">Busto:</label>
                       <input type="text" class="form-control" id="busto" name="busto">
                  </div>
<div class="form-group">
                       <label for="cintura">Cintura:</label>
                       <input type="text" class="form-control" id="cintura" name="cintura">
                  </div>
<div class="form-group">
                       <label for="caderas">Caderas:</label>
                       <input type="text" class="form-control" id="caderas" name="caderas">
                  </div>
<div class="form-group">
                       <label for="detalles_fisicos">Detalles fisicos:</label>
                       <input type="text" class="form-control" id="detalles_fisicos" name="detalles_fisicos">
                  </div>
<div class="form-group">
                       <label for="zonas">Zonas:</label>
                       <input type="text" class="form-control" id="zonas" name="zonas">
                  </div>
<div class="form-group">
                       <label for="citas">Citas:</label>
                       <input type="text" class="form-control" id="citas" name="citas">
                  </div>
<div class="form-group">
                       <label for="salidas">Salidas:</label>
                       <input type="text" class="form-control" id="salidas" name="salidas">
                  </div>
<div class="form-group">
                       <label for="dias">Dias:</label>
                       <input type="text" class="form-control" id="dias" name="dias">
                  </div>
<div class="form-group">
                       <label for="horarios">Horarios:</label>
                       <input type="text" class="form-control" id="horarios" name="horarios">
                  </div>
<div class="form-group">
                       <label for="descripcion_servicio">Descripcion servicio:</label>
                       <input type="text" class="form-control" id="descripcion_servicio" name="descripcion_servicio">
                  </div>
<div class="form-group">
                       <label for="servicios">Servicios:</label>
                       <input type="text" class="form-control" id="servicios" name="servicios">
                  </div>
<div class="form-group">
                       <label for="adicionales">Adicionales:</label>
                       <input type="text" class="form-control" id="adicionales" name="adicionales">
                  </div>
<div class="form-group">
                       <label for="pack_videos">Pack videos:</label>
                       <input type="text" class="form-control" id="pack_videos" name="pack_videos">
                  </div>
<div class="form-group">
                       <label for="otras_atenciones">Otras atenciones:</label>
                       <input type="text" class="form-control" id="otras_atenciones" name="otras_atenciones">
                  </div>
<div class="form-group">
        <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square"></span>Guardar Información pública</button>
    </div>
</form>
 </div>
            </div>
        </div>
    </div>
</div>