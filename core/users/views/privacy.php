<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) {
    $usercode = $_POST["usercode"];
    $verification = $_POST["verification"];
    $nationality = $_POST["nationality"];
    $type_document = $_POST["type_document"];
    $number_document = $_POST["number_document"];
    $name_company = $_POST["name_company"];
    $ruc_number = $_POST["ruc_number"];
    $logo_image = $_POST["logo_image"];
    $required = $_POST["required"];
    $created = $_POST["created"];
    $updated = $_POST["updated"];

    $query = "UPDATE users_privacy SET usercode = ?, verification = ?, nationality = ?, type_document = ?, number_document = ?, name_company = ?, ruc_number = ?, logo_image = ?, required = ?, created = ?, updated = ? WHERE idPri = ?";
    $updated = $conn->prepare($sql);
    $updated->bind_param('ssssssssissi', $usercode, $verification, $nationality, $type_document, $number_document, $name_company, $ruc_number, $logo_image, $required, $created, $updated, $id);
    $updated->execute();
    $updated->close();
}
?> 

<form method="post" class="form-horizontal" role="form" id="add_users_privacy" enctype="multipart/form-data">
<div class="form-group">
  <label for="usercode">Usercode:</label>
  <input type="text" class="form-control" id="usercode" name="usercode">
</div>
<div class="form-group">
  <label for="verification">Verification:</label>
  <input type="text" class="form-control" id="verification" name="verification">
</div>
<div class="form-group">
  <label for="nationality">Nationality:</label>
  <input type="text" class="form-control" id="nationality" name="nationality">
</div>
<div class="form-group">
  <label for="type_document">Type document:</label>
  <select class="form-select" id="type_document" name="type_document" >
<option value="DNI">DNI</option>
<option value="Carnet Extranjeria">Carnet Extranjeria</option>
<option value="Pasaporte">Pasaporte</option>
</select>
</div>
<div class="form-group">
  <label for="number_document">Number document:</label>
  <input type="text" class="form-control" id="number_document" name="number_document">
</div>
<div class="form-group">
  <label for="name_company">Name company:</label>
  <input type="text" class="form-control" id="name_company" name="name_company">
</div>
<div class="form-group">
  <label for="ruc_number">Ruc number:</label>
  <input type="text" class="form-control" id="ruc_number" name="ruc_number">
</div>
<div class="form-group">
  <label for="logo_image">Logo image:</label>
  <input type="text" class="form-control" id="logo_image" name="logo_image">
</div>
<div class="form-group">
  <label for="required">Required:</label>
  <input type="text" class="form-control" id="required" name="required">
</div>
<div class="form-group">
  <label for="created">Created:</label>
  <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="created" name="created">
</div>
<script type="text/javascript">
 $(document).ready(function() {
  $("#created").datepicker({
weekStart: 1,
daysOfWeekHighlighted: "6,0",
autoclose: true,
todayHighlight: true
  });
  $("#created").datepicker("setDate", new Date());
 });
</script>
<div class="form-group">
  <label for="updated">Updated:</label>
  <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="updated" name="updated">
</div>
<script type="text/javascript">
 $(document).ready(function() {
  $("#updated").datepicker({
weekStart: 1,
daysOfWeekHighlighted: "6,0",
autoclose: true,
todayHighlight: true
  });
  $("#updated").datepicker("setDate", new Date());
 });
</script>
<div class="form-group">
  <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square"></span> Add</button>
 </div>
</form>
