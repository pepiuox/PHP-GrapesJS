<?php
require 'conn.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <title>Content Editor</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" data-type="keditor-style"/>
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" data-type="keditor-style" />
    </head>
    <body>
        <?php
// Edit page properties
        if (isset($_POST['submit']) && !empty($_FILES['image'])) {

            //
            $errors = array();
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            // $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

            $extensions = array(
                "jpeg",
                "jpg",
                "png"
            );

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be excately 2 MB';
            }

            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, "uploads/" . $file_name);
                echo "Success";
            } else {
                print_r($errors);
            }

            $title = $_POST['title'];
            $link = strtolower(str_replace(" ", "-", $_POST['link']));
            $keyword = $_POST['keyword'];
            $classification = $_POST['classification'];
            $description = $_POST['description'];
            $active = $_POST['active'];

            $sql = "INSERT INTO page ( title, link, keyword, classification, description, image, active) VALUES ('" . protect($title) . "', '" . protect($link) . "', '" . protect($keyword) . "', '" . protect($classification) . "', '" . protect($description) . "', '" . protect($file_name) . "', '" . protect($active) . "')";
            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                $sqlm = "INSERT INTO menu (page_id, title, link) VALUES ('" . $last_id . "', '" . protect($title) . "', '" . protect($link) . "')";
                if ($conn->query($sqlm) === TRUE) {
                    echo "Page " . $title . " : Created ";
                } else {
                    echo "Failed";
                }
            } else {
                echo "Failed";
            }
        } else {
            echo 'Select an image for the reference page';
        }

        echo '<h3>Add new page</h3>' . "\n";
        echo '<form method="post" enctype="multipart/form-data">' . "\n";
        echo '<div class="row"><div class="col-md-6">' . "\n";
        echo '<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title">
  </div>' . "\n";
        echo '</div><div class="col-md-6">' . "\n";
        echo '<div class="form-group">
    <label for="link">Link</label>
    <input type="text" class="form-control" id="link" name="link">
  </div>' . "\n";
        echo '</div></div><div class="form-group">
    <label for="keyword">Keyword</label>
    <input type="text" class="form-control" id="keyword" name="keyword">
  </div>' . "\n";
        echo '<div class="form-group">
    <label for="classification">Classification</label>
    <input type="text" class="form-control" id="classification" name="classification">
  </div>' . "\n";
        echo '<div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" id="description" name="description">
  </div>' . "\n";
        echo '<div class="form-group">
    <label for="image">Image:</label>
    <input type="file" class="form-control" id="imagen" name="image">
  </div>' . "\n";
        echo '<div class="form-group">
    <label for="active">Active</label>
    <select class="form-control" id="active" name="active">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
  </div>' . "\n";
        echo '<input type="submit" name="submit" class="btn btn-primary" value="Save">' . "\n";
        echo '</form>' . "\n";
        ?>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>        
        <script src="js/popper.min.js" type="text/javascript"></script>
        <script>
            $(function () {
                $("#title").keyup(function () {

                    var value = $(this).val();
                    value = value.toLowerCase();

                    value = value.replace(/ /g, "-");
                    $("#link").val(value);
                }).keyup();
            });
        </script>
    </body>
</html>