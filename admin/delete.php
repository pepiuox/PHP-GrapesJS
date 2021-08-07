<?php
session_start();
$file = '../config/dbconnection.php';
if (file_exists($file)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
    $check = new CheckValidUser();
} else {
    header('Location: install.php');
}
if ($login->isLoggedIn() === true) {
    if (isset($_POST['submit'])) {
        $sql = "DELETE FROM page WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-primary" role="alert">';
            echo "<h4>Record deleted successfully</h4>";
            echo '</div>';
            echo "<script>
window.setTimeout(function() {
    window.location.href = 'list.php';
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

                            <h2>Are you sure you want to delete this page</h2>
                            <?php
                            echo '<form metho>';
                            echo '<input type="submit" name="submit" class="btn btn-primary" value="Detele">' . "\n";
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
} else {
    header('Location: ../index.php');
}
?>