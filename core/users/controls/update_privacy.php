<!-- Start modal edit privacy -->
<div class="modal fade" id="edprivacy" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
  <div class="modal-header">
<h1 class="modal-title fs-5" id="privacyLabel">Datos del registrante y empresa.</h1>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
		<?php
//This is temporal file only for add new row
if (isset($_POST['updateprivacy'])) { 

$nationality = protect($_POST["nationality"]); 
$type_document = protect($_POST["type_document"]); 
$number_document = protect($_POST["number_document"]); 
$name_company = protect($_POST["name_company"]); 
$ruc_number = protect($_POST["ruc_number"]); 

$sql = "UPDATE users_privacy SET nationality = ?, type_document = ?, number_document = ?, name_company = ?, ruc_number = ? WHERE usercode = ?";
$stmt = $uconn>prepare($sql);
$stmt->bind_param('ssssss', $nationality, $type_document, $number_document, $name_company, $ruc_number, $ucode );
$stmt->execute();
$stmt->close();
}
$upry = $conn->prepare("SELECT nationality, type_document, number_document, name_company, ruc_number FROM users_privacy WHERE usercode = ?");
$upry->bind_param('s', $ucode);
$upry->execute();
$pry = $upry->get_result();
$upry->close();
$rpy = $pry->fetch_assoc();
?>  
<form role="form" id="privacy" method="POST">
			<div class="row">
<div class="form-group">
   <label for="nationality" class ="control-label col-md-6">Nacionalidad:</label>
   <input type="text" class="form-control" id="nationality" name="nationality" value="<?php echo $rpy['nationality']; ?>">
  </div>
<div class="form-group">
   <label for="type_document" class ="control-label col-md-6">Tipo de documento:</label>
   <select class="form-select" id="type_document" name="type_document" >
<option value="DNI">DNI</option>
<option value="Carnet Extranjeria">Carnet Extranjeria</option>
<option value="Pasaporte">Pasaporte</option>
</select>
</div>
				
<div class="form-group">
   <label for="number_document" class ="control-label col-md-6">Número documento:</label>
   <input type="text" class="form-control" id="number_document" name="number_document" value="<?php echo $rpy['nationality']; ?>">
  </div>
<div class="form-group">
   <label for="name_company" class ="control-label col-md-6">Nombre empresa:</label>
   <input type="text" class="form-control" id="name_company" name="name_company" value="<?php echo $rpy['name_company']; ?>">
  </div>
<div class="form-group">
   <label for="ruc_number" class ="control-label col-md-6">Número Ruc:</label>
   <input type="text" class="form-control" id="ruc_number" name="ruc_number" value="<?php echo $rpy['ruc_number']; ?>">
  </div>
<div class="form-group">
<button type="submit" id="updateprivacy" name="updateprivacy" class="btn btn-primary"><span class = "fas fa-edit"></span> Actualizar privacidad</button>
</div>
			</div>
</form>
  </div>
  <div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
  </div>
</div>
  </div>
</div>
<!-- End modal edit privacy -->
