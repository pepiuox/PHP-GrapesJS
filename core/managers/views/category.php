<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = protect($_GET['w']);
}
$id = '';
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = protect($_GET['id']);
}

if ($w == "list") {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="post" class="row form-horizontal" role="form" id="add_categories" enctype="multipart/form-data">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Category name</th><th>Description</th><th><a href="dashboard/post_category/add" class="btn btn-primary" > Add new category</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM categories";
                            $rest = $conn->query($sql);
                            if ($rest->num_rows > 0) {
                                while ($row = $rest->fetch_array()) {
                                    echo '<tr>';
                                    echo '<td>' . $row['category_name'] . '</td><td>' . $row['description'] . '</td>';
                                    echo '<td>';
                                    echo '<a class="btn btn-success" href="dashboard/post_category/edit&id=' . $row['categoryId'] . '"><i class="fas fa-edit" aria-hidden="true"></i></a> ';
                                    echo '<a class="btn btn-danger" href="dashboard/post_category/delete&id=' . $row['categoryId'] . '"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<h4>No categories yet</h4>';
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div> 
    </div>
    <?php
} elseif ($w == "add") {
    if (isset($_POST['addrow'])) {
        $category_name = protect($_POST['category_name']);
        $description = protect($_POST['description']);
        $sql = "INSERT INTO categories (category_name,description) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $category_name, $description);
        $stmt->execute();
        if ($stmt->error) {
            echo "FAILURE! " . $stmt->error;
        } else {
            echo '<script> window.location.replace("dashboard/post_category/list"); </script>';
        }
        $stmt->close();
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary" href="dashboard/post_category/list">Back to List</a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Add Category </h2>
            </div>
            <div class="col-md-12">

                <form method="post" class="row form-horizontal" role="form" id="add_categories" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="category_name">Category name:</label>
                        <input type="text" class="form-control" id="category_name" name="category_name">
                    </div>
                    <div class="form-group">
                        <label for="Description">Description:</label>
                        <input type="text" class="form-control" id="Description" name="Description">
                    </div>
                    <div class="form-group">
                        <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square"></span> Add Category</button>
                    </div>
                </form>

            </div>
        </div> 
    </div>
    <?php
} elseif ($w == "edit") {
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        if (isset($_POST['update'])) {
            $category_name = protect($_POST['category_name']);
            $description = protect($_POST['description']);
            $sql = "UPDATE categories SET category_name = ?, description = ? WHERE categoryId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $category_name, $description, $id);
            $stmt->execute();
            if ($stmt->error) {
                echo "FAILURE! " . $stmt->error;
            } else {
                echo '<script> window.location.replace("dashboard/post_category/list"); </script>';
            }
            $stmt->close();
        }
        $sql = "SELECT * FROM categories WHERE categoryId=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <a class="btn btn-secondary" href="dashboard/post_category/list">Back to List</a>
                </div>
                <div class="col-md-9">
                    <h2 class="text-primary">Edit Category </h2>
                </div>
                <div class="col-md-12">

                    <form method="post" class="row form-horizontal" role="form" id="add_categories" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="category_name">Category name:</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $row['category_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $row['description']; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" id="update" name="update" class="btn btn-primary"><span class="fas fa-plus-square"></span> Edit Category</button>
                        </div>
                    </form>

                </div>
            </div> 
        </div>
        <?php
    }
} elseif ($w == "delete") {
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        if (isset($_POST['delete'])) {
            $sql = "DELETE FROM categories WHERE categoryId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            if ($stmt->error) {
                echo "FAILURE! " . $stmt->error;
            } else {
                echo '<script> window.location.replace("dashboard/post_category/list"); </script>';
            }
            $stmt->close();
        }
    }
    ?>
    <div class="container">
        <div class="row">

        </div> 
    </div>
    <?php
}
