<div class='container'> 
    <div class="row">
        <div class="card py-3">
            <div class="card-body">
                <?php
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
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
                            move_uploaded_file($file_tmp, "../uploads/" . $file_name);
                            echo '<div class="alert alert-success" role="alert">';
                            echo "Success";
                            echo '</div>';
                        } else {
                            print_r($errors);
                        }

                        $title = protect($_POST['title']);
                        $link = protect(strtolower(str_replace(" ", "-", $_POST['link'])));
                        $keyword = protect($_POST['keyword']);
                        $classification = protect($_POST['classification']);
                        $description = protect($_POST['description']);
                        $startpage = protect($_POST['startpage']);
                        $parent = protect($_POST['parent']);
                        $active = protect($_POST['active']);

                        if (empty($_POST['image'])) {
                            $image = $_POST['imagen'];
                        } else {
                            $image = $file_name;
                        }

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
                        $qlv->execute();
                        $prsult = $qlv->get_result();
                        if ($stmt->affected_rows) {

                            $qlv1 = $conn->prepare("UPDATE menu SET title_page = ?, link_page = ?, parent_id = ? WHERE page_id = ?");
                            $qlv1->bind_param("ssii", $title, $link, $parent, $id);
                            $qlv1->execute();
                            $presult = $qlv1->get_result();
                            if ($stmt->affected_rows) {

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
                        echo '<meta http-equiv="refresh" content="3; url=builder.php?id=' . $id . '" />';
                    }
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $qlv1 = $conn->prepare("SELECT * FROM page WHERE id = ?");
                        $qlv1->bind_param("i", $id);
                        $qlv1->execute();
                        $presult = $qlv1->get_result();
                        if ($presult->num_rows > 0) {
                            $row = $presult->fetch_assoc();
                            $title = $row['title'];
                            $link = $row['link'];
                            $keyword = $row['keyword'];
                            $classification = $row['classification'];
                            $description = $row['description'];
                            $image = $row['image'];

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
    <label for="startpage">Is home page</label>
    <select class="form-control" id="startpage" name="startpage">';
                            startpg($row['startpage']);
                            echo '</select>
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
        $(function () {
            $("#title").keyup(function () {

                var value = $(this).val();
                value = value.toLowerCase();

                value = value.replace(/ /g, "-");
                $("#link").val(value);
            }).keyup();
        });
    </script>
