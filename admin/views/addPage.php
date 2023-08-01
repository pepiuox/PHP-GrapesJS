<div class="container">                   
    <div class="row">
        <div class="card py-3">
            <div class="card-body">
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
                        $_SESSION['error'] = "It is necessary to add an image that relates the page";
                    }

                    $title = protect($_POST['title']); // Page name
                    $link = protect(strtolower(str_replace(" ", "-", $_POST['link']))); // Page link
                    $keyword = protect($_POST['keyword']);
                    $classification = protect($_POST['classification']);
                    $description = protect($_POST['description']);
                    $startpage = protect($_POST['startpage']);
                    $parent = protect($_POST['parent']);
                    $active = protect($_POST['active']);
                    $change = 0;

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

                    // Check if parent exist or is empty
                    if (!is_int($parent) || empty($parent)) {
                        $parent = 0;
                    }

                    // Insert info in table PAGE 
                    $sql = "INSERT INTO page (title, link, keyword, classification, description, image, startpage, parent, active) "
                            . "VALUES (?,?,?,?,?,?,?,?,?)";
                    $updp = $conn->prepare($sql);
                    $updp->bind_param("ssssssiii", $title, $link, $keyword, $classification, $description, $file_name, $startpage, $parent, $active);
                    $updp->execute();
                    $last_id = $conn->insert_id;
                    $updp->close();

                    if (!empty($last_id)) {

                        // Insert info in table MENU
                        $sqlm = "INSERT INTO menu (page_id, title_page, link_page, parent_id) "
                                . "VALUES (?, ?, ?, ?)";
                        $updpm = $conn->prepare($sqlm);
                        $updpm->bind_param("issi", $last_id, $title, $link, $parent);
                        $updpm->execute();
                        $last_idm = $conn->insert_id;
                        $updpm->close();
                        if (!empty($last_idm)) {
                            $_SESSION['SuccessMessage'] = "Page " . $title . " : Created ";
                        } else {
                            $_SESSION['ErrorMessage'] = "Failed: The page was not added to the menu";
                        }
                    } else {
                        $_SESSION['ErrorMessage'] = "Failed: The page has not been created";
                    }
                    echo '<script> window.location.replace("builder.php?id=' . $last_id . '"); </script>';
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
    <label for="image">Image</label>
    <input type="file" class="form-control" id="imagen" name="image">
  </div>' . "\n";
                echo '<div class="form-group">
			<label for="type" class ="control-label col-sm-3">Type:</label> 
                            <select class="form-select" id="type" name="type">
                            <option value="Design">Design</option>
                            <option value="File">File</option>
                            <option value="Link">Link</option>
                            </select>
			</div>';

                echo '<div class="form-group">
				<label for="menu" class ="control-label col-sm-3">Menu template:</label>';
                slmenu();
                echo '</div>';

                echo '<div class="form-group">
				<label for="hidden_page" class ="control-label col-sm-3">Hidden page:</label> 
                                <select class="form-select" id="hidden_page" name="hidden_page">
                                 <option value="1">Yes</option>
    <option value="0">No</option>
    </select>
			</div>';
                echo '<div class="form-group">
                       <label for="path_file">Path file:</label>
                       <input type="text" class="form-control" id="path_file" name="path_file">
                  </div>';
                echo '<div class="form-group">
                       <label for="script_name">Script name:</label>
                       <input type="text" class="form-control" id="script_name" name="script_name">
                  </div>';
                echo '<div class="form-group">
                       <label for="template">Template:</label>
                       <input type="text" class="form-control" id="template" name="template">
                  </div>';
                echo '<div class="form-group">
                       <label for="base_template">Base template:</label>
                       <input type="text" class="form-control" id="base_template" name="base_template">
                  </div>';
                echo '<div class="form-group">
    <label for="startpage" class ="control-label col-sm-3">Is home page</label>
    <select class="form-select" id="startpage" name="startpage">
    <option value="1">Yes</option>
    <option value="0">No</option>
</select>
  </div>' . "\n";
                echo '<div class="form-group">
    <label for="parent" class ="control-label col-sm-3">Parent</label>' . "\n";
                echo nparent();
                echo '</div>' . "\n";
                echo '<div class="form-group">
    <label for="active" class ="control-label col-sm-3">Active</label>
    <select class="form-select" id="active" name="active">
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
