<div class="modal fade" id="chgimg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Cambiar imagen</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

<?php
global $conn;
// Check if image file is a actual image or fake image
if (isset($_POST["imgfile"])) {
	if (!empty($_FILES['image']['name'])) {
		$errors = array();
    $target_dir = $_SESSION['folderusr'];
    // Nombre generado por el servidor; nunca se usa el nombre del cliente en la ruta.
    $upload_error = null;
    $filename = \PepiuoX\Security\Upload::storeImage($_FILES["image"], $target_dir, \PepiuoX\Security\Upload::DEFAULT_MAX_BYTES, $upload_error);
    if ($filename !== null) {
			$imgq = $conn->prepare("UPDATE users_profiles SET profile_image = ? WHERE idp = ?  AND mkhash = ?");
                $imgq->execute([$filename, $userid, $hash]);
                $nn = $imgq->rowCount();
                $imgq->closeCursor();
            $_SESSION['success'] = "The file " . htmlspecialchars($filename, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . " has been uploaded.";
			echo '<script></script>';
			
    } else {
        $errors[] = "Sorry, your file was not uploaded.";
        if ($upload_error !== null) {
            $errors[] = $upload_error;
        }
    }
	if (empty($errors) === true) {
        echo '<div class="alert alert-success" role="alert">';
        $_SESSION['success'] = "Success";
        echo '</div>';
        } else {
        foreach ($errors as $key => $item) {
        echo '<div class="alert alert-danger" role="alert">';
        echo "$item <br>";
        echo '</div>';
        }
    }
}

}
?>
<form method="post" enctype="multipart/form-data">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
<div class="row">
<div id="display_image text-center">
<img id="uploaded image" src="<?php echo $_SESSION['folderusr'] . $rpro["profile_image"]; ?>" class="img-fluid" alt="<?php 
					echo $rpro["firstname"] . " " . $rpro["lastname"]; ?>">
</div>
<div class="form-group">
<input type="file" class="form-control" id="image" name="image" onchange="FileValidation(event)" >
</div>
</div>
<input type="submit" id="imgfile" name="imgfile" class="btn btn-primary" value="Guadar cambios">
</form>
<script>
FileValidation = (event) => {
	var uploaded_image = document.getElementById('uploaded image');
    uploaded_image.src = URL.createObjectURL(event.target.files[0]);
    const selected_file = document.getElementById("file");
	if (selected_file.files.length > 0) {
        for (const i = 0; i <= selected_file.files.length - 1; i++) {
            const file_size = selected_file.files.item(i).size;
            const file = Math.round((file_size / 1024));
        }
    }
};
</script>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>       
      </div>
    </div>
  </div>
</div>
