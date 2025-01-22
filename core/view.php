<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

require_once "config/loader.php";

$pages = new Routers();
$visitor = new GetVisitor();

$page = $_SERVER["PHP_SELF"];
$_SESSION["language"] = "";
$initweb = $protocol . $_SERVER["HTTP_HOST"] . "/";
$pg404 = $initweb . "404.php";
$url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$escaped_url = htmlspecialchars($url, ENT_QUOTES, "UTF-8");
$url_path = parse_url($escaped_url, PHP_URL_PATH);
$basename = pathinfo($url_path, PATHINFO_BASENAME);

$extpath = pathinfo($url, PATHINFO_EXTENSION);
$pages->routePages();
if ($pages->GoPage() === true) {
    if ($initweb === $url) {
    $rpx = $pages->InitPage();
} elseif (isset($basename) && !empty($basename)) {
    $npg = $pages->Pages($basename);
    if ($npg == $url) {
        $rpx = $pages->PageDataWeb($basename);
    } else {
        header("Location: $npg");
        die();
    }
}else{
    $pages->routePages();
}

$bid = $rpx["id"];
$syspath = $rpx['system_path'];
$viewpg = $rpx['view_page'];
$title = $rpx["title"];
$plink = $rpx["link"];
$purl = $rpx["url"];
$keyword = $rpx["keyword"];
$classification = $rpx["classification"];
$description = $rpx["description"];
$typepage = $rpx["type"];
$menu = $rpx["menu"];
$pfile = $rpx["path_file"];
$content = $rpx["content"];
$style = $rpx["style"];
$lng = $rpx["language"];

$visitor->pageViews($title);

$language = $_SESSION["language"] = $lng;
$request = $_SERVER["REQUEST_URI"];

if ($viewpg === "public") {

    require_once "elements/top.php";

    if ($typepage === 'Design') {
        echo '<style>' . "\n";
        echo decodeContent($style) . "\n";
        echo '</style>' . "\n";
    }
    ?>
</head>

<body>
    <div id="wrapper">
        <div class='container-fluid' id="content-page">
            <?php
                        require_once "elements/menu.php";
                        if ($typepage === 'File') {
                            include "elements/alerts.php";
                            
                            if ($request === $purl) {
                                require_once $pfile . ".php";
                            }
                        }
                        if ($typepage === 'Design') {
                            $string = decodeContent($content);
                            if (!empty($content)) {
                                $string = str_replace("<body>", "", $string);
                                $string = str_replace("</body>", "", $string);
                            }
                            echo $string . "\n";
                        }
                        require_once "elements/footer.php";
                        ?>
        </div>
    </div>
</body>

</html>
<?php
} else {
 include 'elements/header.php'; 
 ?>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php
    if ($request === $purl) {
        require_once $pfile . ".php";
    }
    require_once 'elements/footer.php';
    ?>
    </div>
</body>

</html>
<?php
}
}
?>
