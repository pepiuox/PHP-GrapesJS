<?php
$p = new Protect();

$id = '';
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $p->secureStr($_GET['id']);
}

if ($cms == "list_posts") {
    ?>
    <div class='container-fluid'>            
        <div class="row">                
            <div class="col-md-12 py-3">
                <a href="<?php echo SITE_PATH; ?>admin/dashboard/add_post" class="btn btn-primary" > Add new page</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>View</th>
                            <th>Title</th>
                            <th>Link</th>
                            <th>Category</th>
                            <th>Active</th>
                            <th>Edit</th>
                            <th>Build</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $presult = $conn->query("SELECT * FROM blog_posts LEFT JOIN categories ON category = categoryId");
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
                                echo '<a href="dashboard/edit_post&id=' . $prow['id'] . '"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                                echo '</td><td>' . "\n";
                                echo '<a href="builder.php?build=blog_posts&id=' . $prow['id'] . '"><i class="fas fa-cog" aria-hidden="true"></i></i></a>';
                                echo '</td><td>' . "\n";
                                echo '<a href="dashboard/deletepost&id=' . $prow['id'] . '"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
                                echo '</td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="8" rowspan="1" style="vertical-align: top;">';
                            echo "<h3>You haven't created a Blog Posts yet.</h3>";
                            echo '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
} elseif ($cms == "add_post") {
    if (isset($_POST['addrow'])) {
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

        $title = $_POST['title'];
        $link = $_POST['link'];
        $category = $_POST['category'];
        $image = $_POST['image'];
        $keyword = $_POST['keyword'];
        $classification = $_POST['classification'];
        $description = $_POST['description'];
        $menu = $_POST['menu'];
        $hidden_blog = $_POST['hidden_blog'];
        $published = $_POST['published'];

        $sql = "INSERT INTO blog_posts (title, link, category, image, keyword, classification, description, menu, hidden_blog, published)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssissssiii', $title, $link, $category, $image, $keyword, $classification, $description, $menu, $hidden_blog, $published);
        $stmt->execute();
        if ($stmt->error) {
            echo "FAILURE! " . $stmt->error;
        } else {
            echo "Updated {$stmt->affected_rows} category";
            header('Location: dashboard/blog_posts/list');
        }
        $stmt->close();
    }
    ?>
    <div class="container">                   
        <div class="row">
            <div class="card py-3">
                <div class="card-body">
                    <form method="post" class="row 
                          form-horizontal" role="form" id="add_blog_posts" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link">Link:</label>
                                <input type="text" class="form-control" id="link" name="link">
                            </div>
                        </div>   
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <?php
                            $catg = $conn->query("SELECT * FROM categories");
                            $ncat = $catg->num_rows;
                            if ($ncat > 0) {
                                ?>
                                <select class="form-select" id="category" name="category">
                                    <option>Select a Category</option>
                                    <?php
                                    while ($ctg = $catg->fetch_array()) {
                                        echo '<option value="'.$ctg['categoryId'].'">'.$ctg['category_name'].'</option>';
                                    }
                                    ?>                                   
                                </select>
                                <?php
                            } else {
                                echo '<h4 class="text-danger">Create a category first before creating the post</h4>';
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>                      
                        <div class="form-group">
                            <label for="keyword">Keyword:</label>
                            <input type="text" class="form-control" id="keyword" name="keyword">
                        </div>
                        <div class="form-group">
                            <label for="classification">Classification:</label>
                            <input type="text" class="form-control" id="classification" name="classification">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" class="form-control" id="author" name="author">
                        </div>
                        <div class="form-group">
                            <label for="menu">Menu:</label>
                            <?php echo slmenu(); ?>
                        </div>
                        <div class="form-group">
                            <label for="hidden_blog">Hidden blog:</label>                           
                            <select class="form-select" id="hidden_blog" name="hidden_blog">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="published">Published:</label>
                            <select class="form-select" id="published" name="published">
                                <option value="1">Publish</option>
                                <option value="0">Unpublished</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_posted">Date posted:</label>
                            <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="date_posted" name="date_posted">
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function ()
                            {
                                $("#date_posted").datepicker({
                                    weekStart: 1,
                                    daysOfWeekHighlighted: "6,0",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                                $("#date_posted").datepicker("setDate", new Date());
                            });
                        </script>
                        <div class="form-group">
                            <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square" onclick="dVals();"></span> Add</button>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
} elseif ($cms == "edit_post") {
    if (isset($_POST['editrow'])) {
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

        $title = $_POST['title'];
        $link = $_POST['link'];
        $category = $_POST['category'];
        $image = $_POST['image'];
        $keyword = $_POST['keyword'];
        $classification = $_POST['classification'];
        $description = $_POST['description'];
        $menu = $_POST['menu'];
        $hidden_blog = $_POST['hidden_blog'];
        $published = $_POST['published'];

        $sql = "UPDATE blog_posts SET title=?', link=?, category=?, image=?, keyword=?, classification=?, description=?, menu=', hidden_blog=?, published=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssissssiiii', $title, $link, $category, $image, $keyword, $classification, $description, $menu, $hidden_blog, $published, $id);
        $stmt->execute();
        if ($stmt->error) {
            echo "FAILURE! " . $stmt->error;
        } else {
            echo "Updated {$stmt->affected_rows} category";
            header('Location: dashboard/blog_posts/list');
        }
        $stmt->close();
    }
    $sql = "SELECT * FROM blog_posts WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    ?>
    <div class="container">                   
        <div class="row">
            <div class="card py-3">
                <div class="card-body">
                    <form method="post" class="row 
                          form-horizontal" role="form" id="add_blog_posts" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $row['title'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link">Link:</label>
                                <input type="text" class="form-control" id="link" name="link"  value="<?php echo $row['link'] ?>">
                            </div>
                        </div>   
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <?php
                            $catg = $conn->query("SELECT * FROM categories");
                            $ncat = $catg->num_rows;
                            if ($ncat > 0) {
                                ?>
                                <select class="form-select" id="category" name="category">
                                    <option>Select a Category</option>
                                    <?php
                                    while ($ctg = $catg->fetch_array()) {
                                        echo '<option value="1">Publish</option>';
                                    }
                                    ?>
                                    <option value="1">Publish</option>
                                    <option value="0">Unpublished</option>
                                </select>
                                <?php
                            } else {
                                echo '<h4 class="text-danger">Create a category first before creating the post</h4>';
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control" id="image" name="image"  value="<?php echo $row['image'] ?>">
                        </div>                      
                        <div class="form-group">
                            <label for="keyword">Keyword:</label>
                            <input type="text" class="form-control" id="keyword" name="keyword"  value="<?php echo $row['keyword'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="classification">Classification:</label>
                            <input type="text" class="form-control" id="classification" name="classification" value="<?php echo $row['classification'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $row['description'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" class="form-control" id="author" name="author"  value="<?php echo $row['author'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="menu">Menu:</label>
                            <?php echo slmenu(); ?>
                        </div>
                        <div class="form-group">
                            <label for="hidden_blog">Hidden blog:</label>                           
                            <select class="form-select" id="hidden_blog" name="hidden_blog">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="published">Published:</label>
                            <select class="form-select" id="published" name="published">
                                <option value="1">Publish</option>
                                <option value="0">Unpublished</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_posted">Date posted:</label>
                            <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="date_posted" name="date_posted" value="">
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function ()
                            {
                                $("#date_posted").datepicker({
                                    weekStart: 1,
                                    daysOfWeekHighlighted: "6,0",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                                $("#date_posted").datepicker("setDate", new Date());
                            });
                        </script>
                        <div class="form-group">
                            <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class="fas fa-plus-square"></span> Update Post</button>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
} elseif ($cms == "delete_post") {
    if (isset($_POST['submit'])) {
        $sql = "DELETE FROM blog_posts WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-primary" role="alert">';
            echo "<h4>Post deleted successfully</h4>";
            echo '</div>';
            echo "<script>
window.setTimeout(function() {
    window.location.href = 'dashboard/list_posts';
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
}
?>
