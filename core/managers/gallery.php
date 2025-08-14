<style>

.navtop {
  background-color: #362f2f;
  height: 60px;
  width: 100%;
  border: 0;
}
.navtop div {
  display: flex;
  margin: 0 auto;
  width: 1000px;
  height: 100%;
}
.navtop div h1, .navtop div a {
  display: inline-flex;
  align-items: center;
}
.navtop div h1 {
  flex: 1;
  font-size: 18px;
  padding: 0;
  margin: 0;
  color: #fff;
  font-weight: 400;
}
.navtop div a {
  padding: 0 20px;
  text-decoration: none;
  color: #b6acac;
  font-weight: 500;
}
.navtop div a i {
  padding: 2px 8px 0 0;
}
.navtop div a:hover {
  color: #fff;
}
.content {
  width: 1000px;
  margin: 0 auto;
}
.content h2 {
  margin: 0;
  padding: 35px 0 15px 0;
  font-size: 26px;
  color: #575353;
  font-weight: 600;
}
.content p {
  margin: 0;
  padding: 15px 0;
  font-size: 16px;
  color: #575353;
  font-weight: 400;
}
.image-popup {
  display: none;
  flex-flow: column;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  z-index: 99999;
}
.image-popup .con {
  display: flex;
  flex-flow: column;
  background-color: #ffffff;
  padding: 25px 25px 0 25px;
  border-radius: 5px;
}
.image-popup .con h3 {
  margin: 0;
  font-size: 20px;
  color: #575353;
  font-weight: 600;
}
.image-popup .con p {
  margin: 0;
  padding: 15px 0 20px 0;
  font-size: 16px;
  color: #575353;
  font-weight: 400;
}
.image-popup .con .trash {
  display: inline-flex;
  align-items: center;
  align-self: flex-end;
  justify-content: center;
  text-decoration: none;
  appearance: none;
  cursor: pointer;
  border: 0;
  background: #be5151;
  color: #FFFFFF;
  padding: 0 12px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 4px;
  width: auto;
  height: 35px;
  box-shadow: 0px 0px 6px 1px rgba(68, 45, 45, 0.1);
  margin: 15px 0 35px 0;
}
.image-popup .con .trash svg {
  fill: #fff;
}
.image-popup .con .trash:hover {
  background-color: #b34343;
}
.home .upload-image {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  appearance: none;
  cursor: pointer;
  border: 0;
  background: #be5151;
  color: #FFFFFF;
  padding: 0 18px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 4px;
  height: 38px;
  box-shadow: 0px 0px 6px 1px rgba(68, 45, 45, 0.1);
  margin: 15px 0 35px 0;
}
.home .upload-image:has(svg) {
  padding: 0 18px 0 14px;
}
.home .upload-image svg {
  margin-right: 7px;
  fill: #e5b9b9;
}
.home .upload-image:hover {
  background-color: #b34343;
}
.home .images {
  display: flex;
  flex-flow: wrap;
  gap: 20px;
}
.home .images a {
  display: block;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  width: 320px;
  height: 200px;
}
.home .images a:hover span {
  opacity: 1;
  transition: opacity 1s;
}
.home .images span {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  position: absolute;
  opacity: 0;
  top: 0;
  left: 0;
  color: #fff;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.2);
  padding: 15px;
  transition: opacity 1s;
}
.upload form {
  padding: 15px 0;
  display: flex;
  flex-flow: column;
  width: 400px;
}
.upload form label {
  display: inline-flex;
  width: 100%;
  padding: 10px 0;
  margin-right: 25px;
  font-size: 16px;
  color: #575353;
}
.upload form input, .upload form textarea {
  padding: 10px;
  width: 100%;
  margin-right: 25px;
  margin-bottom: 15px;
  border: 1px solid #e6e2e2;
  border-radius: 4px;
}
.upload form textarea {
  height: 200px;
}
.upload form button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  appearance: none;
  cursor: pointer;
  border: 0;
  background: #be5151;
  color: #FFFFFF;
  padding: 0 18px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 4px;
  width: 130px;
  height: 38px;
  box-shadow: 0px 0px 6px 1px rgba(68, 45, 45, 0.1);
  margin: 15px 0 35px 0;
}
.upload form button:hover {
  background-color: #b34343;
}
.delete .yesno {
  display: flex;
  gap: 15px;
}
.delete .yesno a {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  appearance: none;
  cursor: pointer;
  border: 0;
  background: #be5151;
  color: #FFFFFF;
  padding: 0 18px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 4px;
  width: 60px;
  height: 38px;
  box-shadow: 0px 0px 6px 1px rgba(68, 45, 45, 0.1);
  margin: 15px 0 35px 0;
}
.delete .yesno a:hover {
  background-color: #b34343;
}
</style>
<?php

// Template header, feel free to customize this
function template_header($title) {
    echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1>Gallery System</h1>
            <a href="index.php">Home</a>
    	</div>
    </nav>
EOT;
}

// Template footer
function template_footer() {
    echo <<<EOT
    </body>
</html>
EOT;
}

// Connect to MySQL database
$pdo = pdo_connect_mysql();
// MySQL query that selects all the images ordered by the date they were uploaded
$stmt = $pdo->query('SELECT * FROM images ORDER BY uploaded_date DESC');
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_header('Gallery') ?>

<div class="content home">

	<h2>Gallery</h2>

	<p>Welcome to the gallery page, you can view the list of images below.</p>

	<a href="upload.php" class="upload-image"><svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" /></svg>Upload Image</a>

	<div class="images">
<?php foreach ($images as $image): ?>
    <?php if (file_exists($image['filepath'])): ?>
        		<a href="#">
        			<img src="<?= $image['filepath'] ?>" alt="<?= htmlspecialchars($image['description_text'], ENT_QUOTES) ?>" data-id="<?= $image['id'] ?>" data-title="<?= htmlspecialchars($image['title'], ENT_QUOTES) ?>" width="320" height="200">
        			<span><?= htmlspecialchars($image['description_text'], ENT_QUOTES) ?></span>
        		</a>
    <?php endif; ?>
<?php endforeach; ?>
	</div>

</div>

<div class="image-popup"></div>
<script>
// Container we'll use to show an image
const imagePopup = document.querySelector('.image-popup');
// Loop each image so we can add the on click event
document.querySelectorAll('.images a').forEach(imgLink => {
	imgLink.onclick = event => {
		event.preventDefault();
		const imgMeta = imgLink.querySelector('img');
		const img = new Image();
		img.onload = () => {
			// Create the pop out image
			imagePopup.innerHTML = `
				<div class="con">
					<h3>${imgMeta.dataset.title}</h3>
					<p>${imgMeta.alt}</p>
					<img src="${img.src}" width="${img.width}" height="${img.height}">
					<a href="delete.php?id=${imgMeta.dataset.id}" class="trash" title="Delete Image"><svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z" /></svg></a>
				</div>
			`;
			imagePopup.style.display = 'flex';
		};
		img.src = imgMeta.src;
	};
});
// Hide the image popup container if user clicks outside the image
imagePopup.onclick = event => {
	if (event.target.className == 'image-popup') {
		imagePopup.style.display = "none";
	}
};
</script>
<?php
// The output message
$msg = '';
// Check if user uploaded a new image
if (isset($_FILES['image'], $_POST['title'], $_POST['description'])) {
    // The folder where the images will be stored
    $target_dir = 'images/';
    // The path of the new uploaded image
    $image_path = $target_dir . basename($_FILES['image']['name']);
    // Check to make sure the image is valid
    if (!empty($_FILES['image']['tmp_name']) && getimagesize($_FILES['image']['tmp_name'])) {
        // Validation checks
        if (file_exists($image_path)) {
            $msg = 'Image already exists! Please choose another or rename that image.';
        } else if ($_FILES['image']['size'] > 500000) {
            $msg = 'Image file size too large! Please choose an image less than 500kb.';
        } else {
            // Everything checks out, so now we can move the uploaded image
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            // Connect to MySQL database
            $pdo = pdo_connect_mysql();
            // Insert image info into the database (title, description, image path, and date added)
            $stmt = $pdo->prepare('INSERT INTO images (title, description_text, filepath, uploaded_date) VALUES (?, ?, ?, ?)');
            $stmt->execute([$_POST['title'], $_POST['description'], $image_path, date('Y-m-d H:i:s')]);
            // Output success message
            $msg = 'Image uploaded successfully!';
        }
    } else {
        // No image uploaded or invalid image
        $msg = 'Please upload an image!';
    }
}
?>
<?= template_header('Upload Image') ?>

<div class="content upload">

	<h2>Upload Image</h2>

	<form action="upload.php" method="post" enctype="multipart/form-data">

		<label for="image">Choose Image</label>
		<input type="file" name="image" accept="image/*" id="image" required>

		<label for="title">Title</label>
		<input type="text" name="title" id="title" placeholder="Title" required>

		<label for="description">Description</label>
		<textarea name="description" id="description" placeholder="Description"></textarea>

	    <button type="submit" name="submit">Upload Image</button>

	</form>

	<p><?= $msg ?></p>

</div>

<?= template_footer() ?>
<?php
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// The output message
$msg = '';
// Check that the image ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM images WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    // If no image exists...
    if (!$image) {
        exit('Image doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete file and delete record
            unlink($image['filepath']);
            $stmt = $pdo->prepare('DELETE FROM images WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            // Output msg
            $msg = 'You have deleted the image!';
        } else {
            // User clicked the "No" button, redirect them back to the home/index page
            header('Location: index.php');
            exit;
        }
    }
} else {
    // No GET param provided
    exit('No ID specified!');
}
?>
<?= template_header('Delete') ?>

<div class="content delete">

	<h2>Delete Image #<?= $image['id'] ?></h2>

<?php if ($msg): ?>

        <p><?= $msg ?></p>

<?php else: ?>

    	<p>Are you sure you want to delete <?= htmlspecialchars($image['title'], ENT_QUOTES) ?>?</p>

        <div class="yesno">
            <a href="delete.php?id=<?= $image['id'] ?>&confirm=yes">Yes</a>
            <a href="delete.php?id=<?= $image['id'] ?>&confirm=no">No</a>
        </div>
<?php endif; ?>

</div>

<?= template_footer() ?>
