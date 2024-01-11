<div class="modal fade" id="chgimg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Cambiar imagen</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

<?php
// Check if image file is a actual image or fake image
if (isset($_POST["imgfile"])) {
	if (!empty($_FILES['image']['name'])) {
		$errors = array();
	$filename = $_FILES["image"]["name"];
    $target_dir = $_SESSION['folderusr'];
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["image"]["tmp_name"]);
	 $dirtemp = $target_dir."temp/".$filename."";
    if ($check !== false) {
        $errors[] = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $errors[] = "File is not an image.";
        $uploadOk = 0;
    }

// Check if file already exists
    if (file_exists($target_file)) {
        $errors[] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

// Check file size
    if ($_FILES["file"]["size"] > 1024000) {
        $errors[] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $errors[] = "Sorry, only JPG, JPEG & PNG files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $errors[] = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
			
			$imgq = $uconn->prepare("UPDATE users_profiles SET profile_image = ? WHERE idp = ?  AND mkhash = ?");
                $imgq->bind_param("sss", $filename, $userid, $hash);
                $nn = $imgq->affected_rows;
                $imgq->execute();
                $imgq->close();
            $_SESSION['success'] = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
			echo '<script></script>';
			
        } else {
            $errors[] = "Sorry, there was an error uploading your file.";
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
