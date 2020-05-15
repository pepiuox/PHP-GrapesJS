<?php
require 'conn.php';
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
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
            <div class="container">
                <div class="row">
                    <div class="col-md-12 pt-4">
                        <div class="align-content-end">
                            <a class="btn btn-primary" href="list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                        </div>
                    </div>
                    <div class="col-md-12 py-3">
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
                                echo '<div class="alert alert-success" role="alert">';
                                echo "Success";
                                echo '</div>';
                            } else {
                                print_r($errors);
                            }

                            $title = $_POST['title'];
                            $link = strtolower(str_replace(" ", "-", $_POST['link']));
                            $keyword = $_POST['keyword'];
                            $classification = $_POST['classification'];
                            $description = $_POST['description'];
                            $parent = $_POST['parent'];
                            $active = $_POST['active'];

                            if (empty($_POST['image'])) {
                                $image = $_POST['imagen'];
                            } else {
                                $image = $file_name;
                            }

                            $sql = "UPDATE page SET title ='" . protect($title) . "', link ='" . protect($link) . "', keyword ='" . protect($keyword) . "', classification = '" . protect($classification) . "', description='" . protect($description) . "', image ='" . protect($file_name) . "', parent = '" . protect($parent) . "', active = '" . protect($active) . "' WHERE id='$id'";
                            if ($conn->query($sql) === TRUE) {

                                $sqlm = "UPDATE menu SET title_page = '" . protect($title) . "', link_page = '" . protect($link) . "', parent_id = '" . protect($parent) . "' WHERE page_id='$id'";
                                if ($conn->query($sqlm) === TRUE) {
                                    echo '<div class="alert alert-success" role="alert">';
                                    echo "Page " . $title . " : Created ";
                                    echo '</div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo "Failed: The page was not added to the menu";
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">';
                                echo "Failed: The page has not been created";
                                echo '</div>';
                            }
                            echo '<meta http-equiv="refresh" content="3; url=builder.php?id=' . $id . '" />';
                        }
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $row = $conn->query("SELECT * FROM page WHERE id='$id'")->fetch_assoc();
                            $title = $row['title'];
                            $link = $row['link'];
                            $keyword = $row['keyword'];
                            $classification = $row['classification'];
                            $description = $row['description'];
                            $image = $row['image'];
                        }
                        echo '<h3>Edit page: ' . $title . '</h3>' . "\n";
                        echo '<form method="post" enctype="multipart/form-data">' . "\n";
                        echo '<div class="row"><div class="col-md-6">' . "\n";
                        echo '<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="' . $title . '">
  </div>' . "\n";
                        echo '</div><div class="col-md-6">' . "\n";
                        echo '<div class="form-group">
    <label for="link">Link</label>
    <input type="text" class="form-control" id="link" name="link" value="' . $link . '">
  </div>' . "\n";
                        echo '</div></div><div class="form-group">
    <label for="keyword">Keyword</label>
    <input type="text" class="form-control" id="keyword" name="keyword" value="' . $keyword . '">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="classification">Classification</label>
    <input type="text" class="form-control" id="classification" name="classification" value="' . $classification . '">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" id="description" name="description" value="' . $description . '">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="image">Image:</label>
    <input type="file" class="form-control" id="image" name="image">
        <input type="text" class="form-control" id="imagen" name="imagen" value="' . $image . '" readonly="readonly">
  </div>' . "\n";
                        echo '<div class="form-group">
    <label for="parent">Parent</label>' . "\n";
                        pparent($row['parent']);
                        echo '</div>' . "\n";

                        echo '<div class="form-group">
    <label for="active">Active</label>
    <select class="form-control" id="active" name="active">';
                        action($row['active']);
                        echo '</select>
  </div>' . "\n";
                        echo '<input type="submit" name="submit" class="btn btn-primary" value="Save">' . "\n";
                        echo '</form>' . "\n";
                        ?>
                    </div>
                </div>
            </div>
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
    <?php
} else {
    header('Location: list.php');
}
?>