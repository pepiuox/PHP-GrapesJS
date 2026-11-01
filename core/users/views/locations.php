<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) {
    $user_type = $_POST["user_type"];
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

    $query = "UPDATE users_locations SET user_type = ?, name_location_1 = ?, address_1 = ?, name_location_2 = ?, address_2 = ?, name_location_3 = ?, address_3 = ?, name_location_4 = ?, address_4 = ?, name_location_5 = ?, address_5 = ? WHERE usercode = ?";

    // ✅ Correcciones: (1) prepare() recibía `$sql` (indefinido) en lugar de `$query`;
    //    (2) `$id` nunca se definía, así que el UPDATE no afectaba a ninguna fila.
    //    La columna es `usercode`, que según la convención de la aplicación
    //    (core/users/personalInfo.php) corresponde a $_SESSION['access_id'].
    $rowId = $_SESSION['access_id'] ?? null;

    if ($rowId !== null) {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_type, $name_location_1, $address_1, $name_location_2, $address_2, $name_location_3, $address_3, $name_location_4, $address_4, $name_location_5, $address_5, $rowId]);
        $stmt->closeCursor();
    }
}
?> 

<form role="form" id="edit_users_locations" method="POST">
<?php echo \PepiuoX\Security\Csrf::field(); ?>

<div class="form-group">
<label for="user_type" class ="control-label col-md-6">User type:</label> 
<input type="text" class="form-control" id="user_type" name="user_type" value="">
</div>
<div class="form-group">
  <label for="name_location_1" class ="control-label col-md-6">Name location 1:</label>
  <input type="text" class="form-control" id="name_location_1" name="name_location_1" value="">
</div>
<div class="form-group">
  <label for="address_1" class ="control-label col-md-6">Address 1:</label>
  <input type="text" class="form-control" id="address_1" name="address_1" value="">
</div>
<div class="form-group">
  <label for="name_location_2" class ="control-label col-md-6">Name location 2:</label>
  <input type="text" class="form-control" id="name_location_2" name="name_location_2" value="">
</div>
<div class="form-group">
  <label for="address_2" class ="control-label col-md-6">Address 2:</label>
  <input type="text" class="form-control" id="address_2" name="address_2" value="">
</div>
<div class="form-group">
  <label for="name_location_3" class ="control-label col-md-6">Name location 3:</label>
  <input type="text" class="form-control" id="name_location_3" name="name_location_3" value="">
</div>
<div class="form-group">
  <label for="address_3" class ="control-label col-md-6">Address 3:</label>
  <input type="text" class="form-control" id="address_3" name="address_3" value="">
</div>
<div class="form-group">
  <label for="name_location_4" class ="control-label col-md-6">Name location 4:</label>
  <input type="text" class="form-control" id="name_location_4" name="name_location_4" value="">
</div>
<div class="form-group">
  <label for="address_4" class ="control-label col-md-6">Address 4:</label>
  <input type="text" class="form-control" id="address_4" name="address_4" value="">
</div>
<div class="form-group">
  <label for="name_location_5" class ="control-label col-md-6">Name location 5:</label>
  <input type="text" class="form-control" id="name_location_5" name="name_location_5" value="">
</div>
<div class="form-group">
  <label for="address_5" class ="control-label col-md-6">Address 5:</label>
  <input type="text" class="form-control" id="address_5" name="address_5" value="">
</div>
<div class="form-group">
  <button type="submit" id="editrow" name="editrow" class="btn btn-primary">
      <span class = "fas fa-edit"></span> Edit
  </button>
 </div>
</form>
