<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    /*
      $p = new Protect();
      $w = '';
      if (isset($_GET['w']) && !empty($_GET['w'])) {
      $w = $p->secureStr($_GET['w']);
      }else{
      header('Location: dashboard.php?cms=list_pages&w=list');
      exit;
      }
     */
    if ($cms == "list_pages") {
        ?>
        <div class='container-fluid'>            
            <div class="row">                
                <div class="col-md-12 py-3">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=add_page" class="btn btn-primary" > Add new page</a>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>View</th>
                                <th>Title</th>
                                <th>Link</th>
                                <th>Parent</th>
                                <th>Active</th>
                                <th>Edit</th>
                                <th>Build</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $presult = $conn->query("SELECT * FROM pages");
                            $pnumr = $presult->num_rows;
                            if ($pnumr > 0) {
                                while ($prow = $presult->fetch_array()) {
                                    echo '<tr><td>';
                                    echo '<a href="' . SITE_PATH . $prow['link'] . '" target="_blank"><i class="fas fa-eye" aria-hidden="true"></i></a>';
                                    echo '</td><td>' . "\n";
                                    echo $prow['title'];
                                    echo '</td><td>' . "\n";
                                    echo clean_string($prow['link']);
                                    echo '</td><td>' . "\n";
                                    vwparent($prow['parent']);
                                    echo '</td><td>' . "\n";
                                    vwaction($prow['active']);
                                    echo '</td><td>' . "\n";
                                    echo '<a href="dashboard.php?cms=edit_page&id=' . $prow['id'] . '"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                                    echo '</td><td>' . "\n";
                                    echo '<a href="builder.php?build=page&id=' . $prow['id'] . '"><i class="fas fa-cog" aria-hidden="true"></i></i></a>';
                                    echo '</td><td>' . "\n";
                                    echo '<a href="dashboard.php?cms=delete_page&&id=' . $prow['id'] . '"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
                                    echo '</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8" rowspan="1" style="vertical-align: top;">';
                                echo "<h3>You haven't created a page yet.</h3>";
                                echo '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    } elseif ($cms == "add_page") {
        if (isset($_POST['submit'])) {
            $file_name = '';
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

                if ($file_size > 4000000) {
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
                $qlv1 = $conn->prepare("SELECT id, startpage FROM pages WHERE startpage=?");
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
            echo '<script> window.location.replace("builder.php?build=page&id=' . $last_id . '"); </script>';
        }
        ?>
        <div class="container">                   
            <div class="row">
                <div class="card py-3">
                    <div class="card-body">

                        <h3>Add new page</h3>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row"><div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title">
                                    </div>
                                </div><div class="col-md-6">
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        <input type="text" class="form-control" id="link" name="link">
                                    </div>
                                </div></div><div class="form-group">
                                <label for="keyword">Keyword</label>
                                <input type="text" class="form-control" id="keyword" name="keyword">
                            </div>
                            <div class="form-group">
                                <label for="classification">Classification</label>
                                <input type="text" class="form-control" id="classification" name="classification">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description">
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="imagen" name="image">
                            </div>
                            <div class="form-group">
                                <label for="type" class ="control-label col-sm-3">Type:</label> 
                                <select class="form-select" id="type" name="type">
                                    <option value="Design">Design</option>
                                    <option value="File">File</option>
                                    <option value="Link">Link</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="menu" class ="control-label col-sm-3">Menu template:</label>';
                                <?php echo slmenu(); ?>
                            </div>

                            <div class="form-group">
                                <label for="hidden_page" class ="control-label col-sm-3">Hidden page:</label> 
                                <select class="form-select" id="hidden_page" name="hidden_page">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="path_file">Path file:</label>
                                <input type="text" class="form-control" id="path_file" name="path_file">
                            </div>
                            <div class="form-group">
                                <label for="script_name">Script name:</label>
                                <input type="text" class="form-control" id="script_name" name="script_name">
                            </div>
                            <div class="form-group">
                                <label for="template">Template:</label>
                                <input type="text" class="form-control" id="template" name="template">
                            </div>
                            <div class="form-group">
                                <label for="base_template">Base template:</label>
                                <input type="text" class="form-control" id="base_template" name="base_template">
                            </div>
                            <div class="form-group">
                                <label for="startpage" class ="control-label col-sm-3">Is home page</label>
                                <select class="form-select" id="startpage" name="startpage">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="parent" class ="control-label col-sm-3">Parent</label>
                                <?php echo nparent(); ?>
                            </div>
                            <div class="form-group">
                                <label for="active" class ="control-label col-sm-3">Active</label>
                                <select class="form-select" id="active" name="active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <input type="submit" name="submit" class="btn btn-primary" value="Save">
                        </form>

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

        <?php
    } elseif ($cms == "edit_page") {
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
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

                    if ($file_size > 4000000) {
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
                    $qlv1 = $conn->prepare("SELECT id, startpage FROM pages WHERE startpage=?");
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
            ?>
            <div class="container"> 
                <div class="row">
                    <div class="card py-3">
                        <div class="card-body">
                            <?php
                            if (isset($_GET['id']) && !empty($_GET['id'])) {
                                $id = $_GET['id'];
                                $qlv2 = $conn->prepare("SELECT * FROM pages WHERE id = ?");
                                $qlv2->bind_param("i", $id);
                                $qlv2->execute();
                                $presult = $qlv2->get_result();
                                if ($presult->num_rows > 0) {
                                    $row = $presult->fetch_assoc();
                                    $title = $row['title'];
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
                       <input type="text" class="form-control" id="path_file" name="path_file" value="' . $row['path_file'] . '">
                  </div>';
                                    echo '<div class="form-group">
                       <label for="script_name" class="control-label col-sm-3">Script name:</label>
                       <input type="text" class="form-control" id="script_name" name="script_name" value="' . $row['script_name'] . '">
                  </div>';
                                    echo '<div class="form-group">
                       <label for="template" class="control-label col-sm-3">Template:</label>
                       <input type="text" class="form-control" id="template" name="template" value="' . $row['template'] . '">
                  </div>';
                                    echo '<div class="form-group">
                       <label for="base_template" class="control-label col-sm-3">Base template:</label>
                       <input type="text" class="form-control" id="base_template" name="base_template" value="' . $row['base_template'] . '">
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

            <?php
        } else {
            header('Location: dashboard.php?cms=list_pages');
            exit;
        }
    } elseif ($cms == "delete_page") {

        if (isset($_POST['submit'])) {
            $sql = "DELETE FROM pages WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                echo '<div class="alert alert-primary" role="alert">';
                echo "<h4>Record deleted successfully</h4>";
                echo '</div>';
                echo "<script>
window.setTimeout(function() {
    window.location.href = 'dashboard.php?cms=list_pages';
}, 3000);
</script>";
            } else {
                echo '<div class="alert alert-danger" role="alert">';
                echo "Error deleting record: " . $conn->error;
                echo '</div>';
            }
        }
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12 pt-4">
                    <div class="align-content-end">
                        <a class="btn btn-primary" href="list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                    </div>
                </div>
                <div class="col-md-12 py-3">

                    <h2>Are you sure you want to delete this page</h2>
                    <?php
                    echo '<form method="post">';
                    echo '<input type="submit" name="submit" class="btn btn-primary" value="Detele">' . "\n";
                    echo '</form>' . "\n";
                    ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        header('Location: dashboard.php?cms=list_pages');
    }
}
?>
