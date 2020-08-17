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
        <div class="container">
            <div class="row">
                <div class="col-md-12 pt-4">
                    <div class="align-content-end">
                        <a class="btn btn-primary" href="list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                    </div>
                </div>
                <div class="col-md-12 py-3">
                    <?php
// Add page properties
                    if (isset($_POST['submit'])) {
                        if (!empty($_FILES['image']['name'])) {
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
                                "png",
                                "gif"
                            );

                            if (in_array($file_ext, $extensions)) {
                                if (file_exists("uploads/" . $file_name)) {
                                    $errors[] = $file_name . " is already exists.";
                                } else {
                                    move_uploaded_file($file_tmp, "uploads/" . $file_name);
                                    echo '<div class="alert alert-success" role="alert">';
                                    echo "Your file was uploaded successfully.";
                                    echo '</div>';
                                }
                            } else {
                                $errors[] = "Extension not allowed, please choose a JPEG, JPG, PNG or GIF file. <br/>Or you have not selected a file";
                            }

                            if ($file_size > 2097152) {
                                $errors[] = 'File size must be excately 2 MB';
                            }

                            if (empty($errors) === true) {
                                echo '<div class="alert alert-success" role="alert">';
                                echo "Success";
                                echo '</div>';
                            } else {
                                foreach ($errors as $key => $item) {
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo "$item <br>";
                                    echo '</div>';
                                }
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo "It is necessary to add an image that relates the page";
                            echo '</div>';
                        }

                        $title = $_POST['title']; // Page name
                        $link = strtolower(str_replace(" ", "-", $_POST['link'])); // Page link
                        $keyword = $_POST['keyword'];
                        $classification = $_POST['classification'];
                        $description = $_POST['description'];
                        $parent = $_POST['parent'];
                        // Check if parent exist or is empty
                        if (!is_int($parent) || empty($parent)) {
                            $parent = 0;
                        }
                        $active = $_POST['active'];

                        // Insert info in table PAGE 
                        $sql = "INSERT INTO page ( title, link, keyword, classification, description, image, parent, active) VALUES ('" . protect($title) . "', '" . protect($link) . "', '" . protect($keyword) . "', '" . protect($classification) . "', '" . protect($description) . "', '" . protect($file_name) . "','" . protect($parent) . "', '" . protect($active) . "')";
                        if ($conn->query($sql) === TRUE) {
                            $last_id = $conn->insert_id;
                            // Insert info in table MENU
                            $sqlm = "INSERT INTO menu (page_id, title_page, link_page, parent_id) VALUES ('" . $last_id . "', '" . protect($title) . "', '" . protect($link) . "', '" . protect($parent) . "')";
                            if ($conn->query($sqlm) === TRUE) {
                                /*
                                // Store in folder pages
                                $directory = 'pages/';
                                //Check if the directory already exists.
                                if (!is_dir($directory)) {
                                    //Directory does not exist, so lets create it.
                                    mkdir($directory, 0755, true);
                                }
                                // Change to the extension you want.
                                $ext_files = ".html";
                                $link_path = $directory . $link . $ext_files;
                                $myfile = fopen($link_path, "w") or die("Unable to open file!");                                 
                                 */
                                // For redirect in php
                                /* $txt = '<?php header("Location: ../view.php?id=' . $last_id . '"); ?>'; */
                                // For redirect in html
                                /*
                                $txt = '<html><head><script>window.location.replace("../view.php?id=' . $last_id . '");</script></head><body></body></html>';
                                fwrite($myfile, $text);
                                fclose($myfile);
                                */
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
                        echo '<meta http-equiv="refresh" content="3; url=builder.php?id=' . $last_id . '" />';
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
    <label for="parent">Parent</label>' . "\n";
                    echo nparent();
                    echo '</div>' . "\n";

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