<?php
require 'conn.php';
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
                <?php
// Edit page properties

                echo '<form metho>';
                echo '<input type="submit" name="submit" class="btn btn-primary" value="Detele">' . "\n";
                echo '</form>' . "\n";
                ?>
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