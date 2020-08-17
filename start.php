<?php
$hostlk = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $lpath = "https";
} else {
    $lpath = "http";
}
$lpath .= "://";
$lpath .= $_SERVER['HTTP_HOST'];
$lpath .= $_SERVER['PHP_SELF'];

$fileName = basename($_SERVER['PHP_SELF']);

$path = basename($_SERVER['REQUEST_URI']);
$fl = $hostlk . $fileName;

if ($fl === $lpath) {
    header("Location: index.php?w=list");
    exit();
}

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $id = (int) $_GET['page'];
    $rs = $conn->query("SELECT * FROM `page` WHERE active='1' AND `id` = '$id'");
    $rpx = $rs->fetch_assoc();
} elseif (isset($basename) && !empty($basename)) {
    $rs = $conn->query("SELECT * FROM `page` WHERE active='1' AND link = '$basename'");
    $rpx = $rs->fetch_assoc();
} else {
    $rs = $conn->query("SELECT * FROM `page` WHERE `starpage` = '1' AND active='1'");
    $rpx = $rs->fetch_array();
    header('Location: viewer.php?page=' . $rpx['id']);
}

$bid = $rpx['id'];
$title = $rpx['title'];
$plink = $rpx['link'];
$keyword = $rpx['keyword'];
$classification = $rpx['classification'];
$description = $rpx['description'];
$cont = $rpx['type'];
$men = $rpx['menu'];
$content = $rpx['content'];
$style = $rpx['style'];
$prnt = $rpx['parent'];
$lng = $rpx['language'];

$_SESSION["language"] = $lng;
$language = $_SESSION["language"];
$base = $hostlk;

if ($bid) {
    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
            <?php if (empty($description)) { ?>
                <meta name="description" content="<?php echo $description; ?>" />
            <?php } if (empty($keyword)) { ?>
                <meta name="keywords" content="<?php echo $keyword; ?>" />
            <?php } if (empty($classification)) { ?>
                <meta name="classification" content="<?php echo $classification; ?>" />
            <?php } ?>
            <title><?php
                echo $title;
                ?></title>
            <link href="plugins/bootstrap-4.4.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" data-type="keditor-style"/>
            <link rel="stylesheet" type="text/css" href="./plugins/font-awesome-4.7.0/css/font-awesome.min.css" data-type="keditor-style" />
            <style>
    <?php
    echo html_entity_decode($style);
    ?>
            </style>
        </head>
        <body>
            <?php
            require 'navbar.php';
            ?>
            <div class='container'>
                <?php
                echo html_entity_decode($content) . "\n";
                ?>
            </div>
            <script src="plugins/jquery-3.5.1/jquery-3.5.1.min.js" type="text/javascript"></script>
            <script src="plugins/bootstrap-4.4.1/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="plugins/popper/popper.min.js" type="text/javascript"></script> 
        </body>
    </html>
    <?php
}
?>

