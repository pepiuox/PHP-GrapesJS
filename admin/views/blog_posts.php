<?php
$p = new Protect();
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $p->secureStr($_GET['w']);
}
if ($w == "list") {
    ?>
    <div class='container-fluid'>            
        <div class="row">                
            <div class="col-md-12 py-3">
                <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=addpage" class="btn btn-primary" > Add new page</a>
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
                        $presult = $conn->query("SELECT * FROM blog_posts");
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
                                echo '<a href="dashboard.php?cms=editpage&id=' . $prow['id'] . '"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                                echo '</td><td>' . "\n";
                                echo '<a href="builder.php?id=' . $prow['id'] . '"><i class="fas fa-cog" aria-hidden="true"></i></i></a>';
                                echo '</td><td>' . "\n";
                                echo '<a href="dashboard.php?cms=deletepage&id=' . $prow['id'] . '"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
                                echo '</td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="8" rowspan="1" style="vertical-align: top;">';
                            echo "<h3>You haven't created a blog_posts yet.</h3>";
                            echo '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
} elseif ($w == "add") {
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
        $category_id = $_POST['category_id'];
        $image = $_POST['image'];
        $post = $_POST['post'];
        $style = $_POST['style'];
        $keyword = $_POST['keyword'];
        $classification = $_POST['classification'];
        $description = $_POST['description'];
        $author_id = $_POST['author_id'];
        $menu = $_POST['menu'];
        $hidden_blog = $_POST['hidden_blog'];
        $published = $_POST['published'];
        $date_posted = $_POST['date_posted'];

        $sql = "INSERT INTO blog_posts (title, link, category_id, image, post, style, keyword, classification, description, author_id, menu, hidden_blog, published, date_posted)
        VALUES ('$title', '$link', '$category_id', '$image', '$post', '$style', '$keyword', '$classification', '$description', '$author_id', '$menu', '$hidden_blog', '$published', '$date_posted')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = 'The data was added correctly';
            header('Location: dashboard.php?cms=blog_posts&w=list');
        } else {
            $_SESSION['error'] = 'Error: ' . $conn->error;
        }

        $conn->close();
    }
    ?>
    <div class="container">                   
        <div class="row">
            <div class="card py-3">
                <div class="card-body">
                    <form method="post" class="row 
                          form-horizontal" role="form" id="add_blog_posts" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="link">Link:</label>
                            <input type="text" class="form-control" id="link" name="link">
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category:</label>
                            <input type="text" class="form-control" id="category_id" name="category_id">
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="text" class="form-control" id="image" name="image">
                        </div>
                        <div class="form-group">
                            <label for="post">Post:</label>
                            <textarea type="text" class="form-control" id="post" name="post"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="style">Style:</label>
                            <textarea type="text" class="form-control" id="style" name="style"></textarea>
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
                            <label for="author_id">Author:</label>
                            <input type="text" class="form-control" id="author_id" name="author_id">
                        </div>
                        <div class="form-group">
                            <label for="menu">Menu:</label>
                            <input type="text" class="form-control" id="menu" name="menu">
                        </div>
                        <div class="form-group">
                            <label for="hidden_blog">Hidden blog:</label>
                            <input type="text" class="form-control" id="hidden_blog" name="hidden_blog">
                        </div>
                        <div class="form-group">
                            <label for="published">Published:</label>
                            <input type="text" class="form-control" id="published" name="published">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
} elseif ($w == "update") {
    
}
