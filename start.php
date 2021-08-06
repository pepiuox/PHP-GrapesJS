<?php
$_SESSION['language'] = '';

$url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
$url_path = parse_url($escaped_url, PHP_URL_PATH);
$basename = pathinfo($url_path, PATHINFO_BASENAME);

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $id = (int) $_GET['page'];
    $rs = $conn->query("SELECT * FROM `page` WHERE active='1' AND `id` = '$id'");
    $rpx = $rs->fetch_assoc();
} elseif (isset($basename) && !empty($basename)) {

    $rs = $conn->query("SELECT * FROM `page` WHERE active='1' AND link = '$basename'");
    $nm = $rs->num_rows;
    if ($nm > 0) {
        $rpx = $rs->fetch_assoc();
    } else {
        $rs = $conn->query("SELECT * FROM `page` WHERE `starpage` = '1' AND active='1'");
        $rpx = $rs->fetch_assoc();
        $namelink = $base.$rpx['link'];

        header("Location: $namelink");
    }
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
            <title><?php echo $title; ?></title>
            <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" data-type="keditor-style"/>
            <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>css/font-awesome.min.css" data-type="keditor-style" />
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
            <script src="<?php echo $base; ?>js/jquery.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>js/popper.min.js" type="text/javascript"></script> 
        </body>
    </html>
    <?php
}
?>

