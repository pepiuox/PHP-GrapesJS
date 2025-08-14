<!-- Start modal edit privacy -->
<div class="modal fade" id="edlocal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
  <div class="modal-header">
<h1 class="modal-title fs-5" id="privacyLabel">Direcciones del negocio</h1>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
		<?php
//This is temporal file only for add new row
//This is temporal file only for add new row
if (isset($_POST['editloc'])) {  
$name_location_1 = $_POST["name_location_1"]; 
$address_1 = $_POST["address_1"]; 
$name_location_2 = $_POST["name_location_2"]; 
$address_2 = $_POST["address_2"]; 
$name_location_3 = $_POST["name_location_3"]; 
$address_3 = $_POST["address_3"]; 
$name_location_4 = $_POST["name_location_4"]; 
$address_4 = $_POST["address_4"]; 
$name_location_5 = $_POST["name_location_5"]; 
$address_5 = $_POST["address_5"]; 

$sql = "UPDATE users_locations name_location_1 = ?, address_1 = ?, name_location_2 = ?, address_2 = ?, name_location_3 = ?, address_3 = ?, name_location_4 = ?, address_4 = ?, name_location_5 = ?, address_5 = ? WHERE usercode = ?";
$updated = $this->conn->prepare($sql);
$updated->bind_param('ssssssssss', $name_location_1, $address_1, $name_location_2, $address_2, $name_location_3, $address_3, $name_location_4, $address_4, $name_location_5, $address_5, $ucode );
$updated->execute();
$updated->close();
}
$upry = $conn->prepare("SELECT name_location_1, address_1, name_location_2, address_2, name_location_3, address_3, name_location_4, address_4, name_location_5, address_5 FROM users_locations WHERE usercode = ?");
$upry->bind_param('s', $ucode);
$upry->execute();
$pry = $upry->get_result();
$upry->close();
$rpy = $pry->fetch_assoc();
?>  
<form role="form" id="privacy" method="POST">
			<div class="row">
<div class="form-group">
   <label for="name_location_1">Name location 1:</label>
   <input type="text" class="form-control" id="name_location_1" name="name_location_1">
  </div>
<div class="form-group">
   <label for="address_1">Address 1:</label>
   <input type="text" class="form-control" id="address_1" name="address_1">
  </div>
<div class="form-group">
   <label for="name_location_2">Name location 2:</label>
   <input type="text" class="form-control" id="name_location_2" name="name_location_2">
  </div>
<div class="form-group">
   <label for="address_2">Address 2:</label>
   <input type="text" class="form-control" id="address_2" name="address_2">
  </div>
<div class="form-group">
   <label for="name_location_3">Name location 3:</label>
   <input type="text" class="form-control" id="name_location_3" name="name_location_3">
  </div>
<div class="form-group">
   <label for="address_3">Address 3:</label>
   <input type="text" class="form-control" id="address_3" name="address_3">
  </div>
<div class="form-group">
   <label for="name_location_4">Name location 4:</label>
   <input type="text" class="form-control" id="name_location_4" name="name_location_4">
  </div>
<div class="form-group">
   <label for="address_4">Address 4:</label>
   <input type="text" class="form-control" id="address_4" name="address_4">
  </div>
<div class="form-group">
   <label for="name_location_5">Name location 5:</label>
   <input type="text" class="form-control" id="name_location_5" name="name_location_5">
  </div>
<div class="form-group">
   <label for="address_5">Address 5:</label>
   <input type="text" class="form-control" id="address_5" name="address_5">
  </div>
<div class="form-group">
<button type="submit" id="updatelocal" name="updatelocal" class="btn btn-primary"><span class = "fas fa-edit"></span> Actualizar ubicaciones</button>
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
