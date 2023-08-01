<div class='container'> 
    <div class="row">
        <div class="card py-3">
            <div class="card-body">
                <?php
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
// Edit page properties
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
                                if (file_exists("../uploads/" . $file_name)) {
                                    $errors[] = $file_name . " is already exists.";
                                } else {
                                    move_uploaded_file($file_tmp, "../uploads/" . $file_name);

                                    $_SESSION['success'] = "Your file was uploaded successfully.";
                                }
                            } else {
                                $errors[] = "Extension not allowed, please choose a JPEG, JPG, PNG or GIF file. <br/>Or you have not selected a file";
                            }

                            if ($file_size > 5000000) {
                                $errors[] = 'File size must be excately 4 MB';
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
                        } else {
                            if (!empty($_POST['imagen'])) {
                                $file_name = $_POST['imagen'];
                            } else {
                                $_SESSION['error'] = "It is necessary to add an image that relates the page";
                            }
                        }

                        $title = protect($_POST['title']);
                        $link = protect(strtolower(str_replace(" ", "-", $_POST['link'])));
                        $keyword = protect($_POST['keyword']);
                        $classification = protect($_POST['classification']);
                        $description = protect($_POST['description']);
                        $startpage = protect($_POST['startpage']);
                        $image = protect($_POST['image']);
                        $menu = protect($_POST['menu']);
                        $type = protect($_POST['type']);
                        $path_file = protect($_POST['path_file']);
                        $script_name = protect($_POST['script_name']);
                        $template = protect($_POST['template']);
                        $base_template = protect($_POST['base_template']);
                        $hidden_page = protect($_POST['hidden_page']);
                        $parent = protect($_POST['parent']);
                        $active = protect($_POST['active']);

                        if ($startpage === 1) {
                            $qlv1 = $conn->prepare("SELECT id, startpage FROM page WHERE startpage=?");
                            $qlv1->bind_param("i", $startpage);
                            $qlv1->execute();
                            $presult = $qlv1->get_result();
                            $qlv1->close();
                            if ($presult->num_rows > 0) {
                                $dt = $presult->fetch_assoc();
                                $idsp = $dt['id'];
                                $updp = $conn->prepare("UPDATE page SET startpage=? WHERE id=?");
                                $updp->bind_param("ii", $change, $idsp);
                                $updp->execute();
                                $updp->close();
                            }
                        }

                        $qlv = $conn->prepare("UPDATE page SET title = ?, link = ?, keyword = ?, classification = ?, description= ?, image = ?, startpage = ?, parent = ?, active = ? WHERE id = ?");
                        $qlv->bind_param("ssssssiiii", $title, $link, $keyword, $classification, $description, $file_name, $startpage, $parent, $active, $id);
                        $nn = $qlv->affected_rows;
                        $qlv->execute();

                        if ($nn === 0) {

                            $qlv1 = $conn->prepare("UPDATE menu SET title_page = ?, link_page = ?, parent_id = ? WHERE page_id = ?");
                            $qlv1->bind_param("ssii", $title, $link, $parent, $id);
                            $nn1 = $qlv1->affected_rows;
                            $qlv1->execute();

                            if ($nn1 === 0) {

                                echo '<div class="alert alert-success" role="alert">';
                                echo "Page " . $title . " : Updated ";
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-danger" role="alert">';
                                echo "Failed: The page was not updated to the menu";
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo "Failed: The page has not been updated";
                            echo '</div>';
                        }
                        // echo '<meta http-equiv="refresh" content="5; url=builder.php?id=' . $id . '" />';
                    }
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $qlv2 = $conn->prepare("SELECT * FROM page WHERE id = ?");
                        $qlv2->bind_param("i", $id);
                        $qlv2->execute();
                        $presult = $qlv2->get_result();
                        if ($presult->num_rows > 0) {
                            $row = $presult->fetch_assoc();
                            
                            echo '<h3>Edit page: ' . $title . '</h3>' . "\n";
                            echo '<form method="post" enctype="multipart/form-data">' . "\n";
                            echo '<div class="row"><div class="col-md-6">' . "\n";
                            echo '<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="' . $row['title'] . '">
  </div>' . "\n";
                            echo '</div><div class="col-md-6">' . "\n";
                            echo '<div class="form-group">
    <label for="link">Link</label>
    <input type="text" class="form-control" id="link" name="link" value="' . $row['link'] . '">
  </div>' . "\n";
                            echo '</div></div><div class="form-group">
    <label for="keyword">Keyword</label>
    <input type="text" class="form-control" id="keyword" name="keyword" value="' . $row['keyword'] . '">
  </div>' . "\n";
                            echo '<div class="form-group">
    <label for="classification">Classification</label>
    <input type="text" class="form-control" id="classification" name="classification" value="' . $row['classification'] . '">
  </div>' . "\n";
                            echo '<div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" id="description" name="description" value="' . $row['description'] . '">
  </div>' . "\n";
                            echo '<div class="form-group row">
                                <div class="col-3">
                                <img src="../uploads/' . $row['image'] . '" class="img-rounded" width="250px" height="250px" />
                                    </div>
                                    <div class="col-9">
    <label for="image">Image:</label>
    <input type="file" class="form-control" id="image" name="image">
        <input type="text" class="form-control" id="imagen" name="imagen" value="' . $row['image'] . '" readonly="readonly">
  </div>
  </div>' . "\n";
                                                     
                          enum_values('page', 'type', $row['type']);

                            echo '<div class="form-group">
				<label for="menu" class ="control-label col-sm-3">Menu:</label> 
                                ';
                            menuopt($row['menu']);
                            echo '</div>';
                             echo '<div class="form-group">
				<label for="hidden_page" class ="control-label col-sm-3">Hidden page:</label> 
                                <select class="form-select" id="hidden_page" name="hidden_page">';
                            action($row['hidden_page']);
			echo '</select></div>';
                            echo '<div class="form-group">
                       <label for="path_file" class="control-label col-sm-3">Path file:</label>
                       <input type="text" class="form-control" id="path_file" name="path_file" value="'.$row['path_file'].'">
                  </div>';
echo '<div class="form-group">
                       <label for="script_name" class="control-label col-sm-3">Script name:</label>
                       <input type="text" class="form-control" id="script_name" name="script_name" value="'.$row['script_name'].'">
                  </div>';
echo '<div class="form-group">
                       <label for="template" class="control-label col-sm-3">Template:</label>
                       <input type="text" class="form-control" id="template" name="template" value="'.$row['template'].'">
                  </div>';
echo '<div class="form-group">
                       <label for="base_template" class="control-label col-sm-3">Base template:</label>
                       <input type="text" class="form-control" id="base_template" name="base_template" value="'.$row['base_template'].'">
                  </div>';
                           
                            echo '<div class="form-group">
    <label for="startpage">Is home page</label>
    <select class="form-select" id="startpage" name="startpage">';
                            startpg($row['startpage']);
                            echo '</select>
  </div>' . "\n";
                            echo '<div class="form-group">
    <label for="parent">Parent</label>' . "\n";
                            pparent($row['parent']);
                            echo '</div>' . "\n";

                            echo '<div class="form-group">
    <label for="active">Active</label>
    <select class="form-select" id="active" name="active">';
                            action($row['active']);
                            echo '</select>
  </div>' . "\n";
                            echo '<input type="submit" name="submit" class="btn btn-primary" value="Save">' . "\n";
                            echo '</form>' . "\n";
                        }
                    }
                } else {
                    header('Location: dashboard.php?cms=pagelist');
                    exit();
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("title").addEventListener("keyup", mkeyup);
    function mkeyup() {
        let ttl = document.getElementById("title").value;
        ttl = ttl.toLowerCase();
        ttl = ttl.replace(/ /g, "-");
         document.getElementById("link").value = ttl;
   }
</script>
