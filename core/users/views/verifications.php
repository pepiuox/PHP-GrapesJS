<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$verification = $_POST["verification"]; 
$is_verify = $_POST["is_verify"]; 
$portraid_date = $_POST["portraid_date"]; 
$image_document_front = $_POST["image_document_front"]; 
$image_document_back = $_POST["image_document_back"]; 
$payment_document = $_POST["payment_document"]; 

$query = "UPDATE users_verifications SET verification = ?, is_verify = ?, portraid_date = ?, image_document_front = ?, image_document_back = ?, payment_document = ? WHERE usercode = ?";
$updated = $conn->prepare($sql);
$updated->bind_param('sissssi', $verification, $is_verify, $portraid_date, $image_document_front, $image_document_back, $payment_document, $id );
$updated->execute();
$updated->close();
}
?> 

<form role="form" id="edit_users_verifications" method="POST">

<div class="form-group">
                       <label for="verification" class ="control-label col-md-6">Verification:</label>
                       <input type="text" class="form-control" id="verification" name="verification" value="">
                  </div>
<div class="form-group">
				<label for="is_verify" class ="control-label col-md-6">Is verify:</label> <input type="text"
					class="form-control" id="is_verify" name="is_verify"
					value="">
			</div>
<div class="form-group">
                       <label for="portraid_date" class ="control-label col-md-6">Portraid date:</label>
                       <input type="text" class="form-control" id="portraid_date" name="portraid_date" value="">
                  </div>
<div class="form-group">
                       <label for="image_document_front" class ="control-label col-md-6">Image document front:</label>
                       <input type="text" class="form-control" id="image_document_front" name="image_document_front" value="">
                  </div>
<div class="form-group">
                       <label for="image_document_back" class ="control-label col-md-6">Image document back:</label>
                       <input type="text" class="form-control" id="image_document_back" name="image_document_back" value="">
                  </div>
<div class="form-group">
                       <label for="payment_document" class ="control-label col-md-6">Payment document:</label>
                       <input type="text" class="form-control" id="payment_document" name="payment_document" value="">
                  </div>
<div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>
    </div>
</form>
