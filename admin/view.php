<?php
session_start();
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
    $check = new CheckValidUser();
    $level = new AccessLevel();
} else {
    header('Location: ../installer/install.php');
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $spg = $conn->prepare("SELECT * FROM page WHERE id=?");
    $spg->bind_param("i", $id);
    $spg->execute();
    $rs = $spg->get_result();
    $nm = $rs->num_rows;
    if ($nm > 0) {
        $row = $rs->fetch_assoc();
        $bid = $row['id'];
        $title = $row['title'];
        $plink = $row['link'];
        $keyword = $row['keyword'];
        $classification = $row['classification'];
        $description = $row['description'];
        $cont = $row['type'];
        $menu = $row['menu'];
        $content = $row['content'];
        $style = $row['style'];
        $prnt = $row['parent'];
        $lng = $row['language'];
        ?>
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8"/>
                <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
                <?php if (!empty($description)) { ?>
                    <meta name="description" content="<?php echo $description; ?>" />
                <?php } else { ?>
                    <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>" />
                    <?php
                }
                if (!empty($keyword)) {
                    ?>
                    <meta name="keywords" content="<?php echo $keyword; ?>" />
                <?php } else { ?>
                    <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />
                    <?php
                }
                if (!empty($classification)) {
                    ?>
                    <meta name="classification" content="<?php echo $classification; ?>" />
                <?php } else { ?>
                    <meta name="classification" content="<?php echo SITE_CLASSIFICATION; ?>" />
                <?php } ?>
                <title><?php echo $title; ?></title>
                <link href="<?php echo $base; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/font-awesome.min.css" />
                <style>
        <?php
        echo decodeContent($style) . "\n";
        ?>
                </style>
            </head>
            <body>
                <?php
                require '../menu.php';
                ?>
                <div class='container'>
                    <?php
                    echo decodeContent($content) . "\n";
                    ?>
                </div>
                <script src="<?php echo $base; ?>assets/js/jquery.min.js" type="text/javascript"></script>
                <script src="<?php echo $base; ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
                <script src="<?php echo $base; ?>assets/js/popper.min.js" type="text/javascript"></script> 
            </body>
        </html>
        <?php
    } else {
        header('Location: dashboard.php?cms=pagelist');
        exit();
    }
} else {
    header('Location: dashboard.php?cms=pagelist');
    exit();
}
?>
