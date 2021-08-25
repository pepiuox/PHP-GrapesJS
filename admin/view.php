<?php
session_start();
$file = '../config/dbconnection.php';
if (file_exists($file)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
    $check = new CheckValidUser();
    $level = new AccessLevel();
} else {
    header('Location: ../installer/install.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $spg = $conn->prepare("SELECT * FROM page WHERE id=?");
    $spg->bind_param("i", $id);
    $spg->execute();
    $rs = $spg->get_result();
    $nm = $rs->num_rows;

    $row = $rs->fetch_assoc();
    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
            <?php if (empty($description)) { ?>
                <meta name="description" content="<?php echo $row['description']; ?>" />
            <?php } if (empty($keyword)) { ?>
                <meta name="keywords" content="<?php echo $row['keyword']; ?>" />
            <?php } if (empty($classification)) { ?>
                <meta name="classification" content="<?php echo $row['classification']; ?>" />
            <?php } ?>
            <title><?php echo $row['title']; ?></title>
            <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" data-type="keditor-style"/>
            <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>css/font-awesome.min.css" data-type="keditor-style" />
            <style>
    <?php
    echo html_entity_decode($row['style']);
    ?>
            </style>
        </head>
        <body>
            <?php
            require '../navbar.php';
            ?>
            <div class='container'>
                <?php
                echo html_entity_decode($row['content']) . "\n";
                ?>
            </div>
            <script src="<?php echo $base; ?>js/jquery.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>js/popper.min.js" type="text/javascript"></script> 
        </body>
    </html>
    <?php
} else {
    header('Location: dashboard.php?cms=pagelist');
}
?>