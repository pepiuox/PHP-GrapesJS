<!-- Start modal edit info -->
<div class="modal fade" id="edinfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
  <div class="modal-header">
<h1 class="modal-title fs-5" id="infoLabel">Datos del registrante y empresa.</h1>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
		<?php
global $conn;

// ✅ `$ucode` y `$id` no se definían en este archivo: el UPDATE filtraba por
//    `userid = NULL` (no afectaba a ninguna fila) y el SELECT tampoco encontraba el
//    registro. Convención de la aplicación (ver core/users/personalInfo.php):
//    columna `usercode` → $_SESSION['access_id'];  PK `userid` → $_SESSION['user_id'].
$ucode = $_SESSION['access_id'] ?? null;
$rowId = $_SESSION['user_id'] ?? null;
//This is temporal file only for add new row
if (isset($_POST['updateinfo'])) { 

$firstname = protect($_POST["firstname"]); 
$lastname = protect($_POST["lastname"]); 
$gender = protect($_POST["gender"]); 
$age = protect($_POST["age"]); 
$birthday = protect($_POST["birthday"]); 
$phone = protect($_POST["phone"]); 
$country = protect($_POST["country"]); 
$state = protect($_POST["state"]); 
$city = protect($_POST["city"]); 
$address1 = protect($_POST["address1"]); 
$address2 = protect($_POST["address2"]); 
 

// ✅ Correcciones: faltaba un espacio antes de `WHERE` ("address2 = ?WHERE"), lo que
//    producía un error de sintaxis SQL; y el parámetro `$id` no existía.
$sql = "UPDATE users_info SET firstname = ?, lastname = ?, gender = ?, age = ?, birthday = ?, phone = ?, country = ?, state = ?, city = ?, address1 = ?, address2 = ? WHERE userid = ?";

if ($rowId !== null) {
    $stmt = $conn->prepare($sql);
    $stmt->execute([$firstname, $lastname, $gender, $age, $birthday, $phone, $country, $state, $city, $address1, $address2, $rowId]);
    $stmt->closeCursor();
}
}

// ✅ Si no hay sesión o no hay fila, `$uinf` queda con las claves vacías en lugar de
//    provocar avisos de "Undefined array key" en las líneas `value="..."` de abajo.
$uinf = array_fill_keys(
    ['firstname', 'lastname', 'age', 'birthday', 'phone', 'country', 'state', 'city', 'address1', 'address2'],
    ''
);

if ($ucode !== null) {
    $upinfo = $conn->prepare("SELECT * FROM users_info WHERE usercode=?");
    $upinfo->execute([$ucode]);
    $uinf = $upinfo->fetch(PDO::FETCH_ASSOC) ?: $uinf;
    $upinfo->closeCursor();
}
?>  
<form role="form" id="info" method="POST">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
<div class="row">
<div class="form-group">
<label for="firstname" class ="control-label col-md-6">Nombres:</label>
<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $uinf['firstname']; ?>">
</div>
<div class="form-group">
<label for="lastname" class ="control-label col-md-6">Apellidos:</label>
<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $uinf['lastname']; ?>">
</div>
<div class="form-group">
<label for="gender" class ="control-label col-md-6">Genero:</label>
<select class="form-select" id="gender" name="gender" >
<option value="Woman">Mujer</option>
<option value="Male" selected>Varon</option>
<option value="With doubt">Con dudas</option>
</select>
</div>
<div class="form-group">
<label for="age" class ="control-label col-md-6">Edad:</label>
<input type="text" class="form-control" id="age" name="age" value="<?php echo $uinf['age']; ?>">
</div>		
<div class="form-group">
   <label for="birthday" class ="control-label col-md-6">Fecha Nacimiento:</label>
   <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="birthday" name="birthday" value="<?php echo $uinf['birthday']; ?>">
  </div>
<script type="text/javascript">
$(document).ready(function ()
{
$("#birthday").datepicker({
weekStart: 1,
daysOfWeekHighlighted: "6,0",
autoclose: true,
todayHighlight: true
});
$("#birthday").datepicker("setDate", new Date());
});
</script>
<div class="form-group">
   <label for="phone" class ="control-label col-md-6">Telefono:</label>
   <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $uinf['phone']; ?>">
  </div>
<div class="form-group">
   <label for="country" class ="control-label col-md-6">Pais:</label>
   <input type="text" class="form-control" id="country" name="country" value="<?php echo $uinf['country']; ?>">
  </div>
<div class="form-group">
   <label for="state" class ="control-label col-md-6">Estado/Region/Departamento:</label>
   <input type="text" class="form-control" id="state" name="state" value="<?php echo $uinf['state']; ?>">
  </div>
<div class="form-group">
   <label for="city" class ="control-label col-md-6">Ciudad:</label>
   <input type="text" class="form-control" id="city" name="city" value="<?php echo $uinf['city']; ?>">
  </div>
<div class="form-group">
   <label for="address1" class ="control-label col-md-6">Dirección:</label>
   <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $uinf['address1']; ?>">
  </div>
<div class="form-group">
   <label for="address2" class ="control-label col-md-6">Dirección secundaria:</label>
   <input type="text" class="form-control" id="address2" name="address2" value="<?php echo $uinf['address2']; ?>">
  </div>
<div class="form-group">
<button type="submit" id="updateinfo" name="updateinfo" class="btn btn-primary"><span class = "fas fa-edit"></span> Actualizar información</button>
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
<!-- End modal edit perfil -->
