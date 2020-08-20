<?php
/* view Page */
include 'conn.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "SELECT * FROM page WHERE id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Page Builder</title>
            <link href="<?php echo $base; ?>css/theme.css" rel="stylesheet"
                  type="text/css" />
            <link rel="stylesheet" href="<?php echo $base; ?>css/bootnavbar.css">

            <style>
    <?php
    echo html_entity_decode($row['style']);
    ?>
            </style>
        </head>
        <body>

            <?php
            include_once 'menu.php';
            echo html_entity_decode($row['content']);
            ?>
            <script src="<?php echo $base; ?>js/bootstrap.min.js"
            type="text/javascript"></script>
            <script src="<?php echo $base; ?>js/jquery.min.js"
            type="text/javascript"></script>
            <script src="<?php echo $base; ?>js/popper.min.js"
            type="text/javascript"></script>
            <script src="<?php echo $base; ?>js/bootnavbar.js"></script>
            <script>
                $(function () {
                    $('#main_navbar').bootnavbar();
                })
            </script>
        </body>
    </html>
    <?php
}else{
    header('Location: list.php');
}
?>