<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

require_once "../src/config/loader.php";

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

$active = 1;
$startpage = 1;
$nm = "";

if ($initweb === $url) {
    $spg = $conn->prepare(
        "SELECT * FROM page WHERE startpage = ? AND active = ? "
    );
    $spg->bind_param("ii", $startpage, $active);
    $spg->execute();
    $rs = $spg->get_result();
    $spg->close();
    $nm = $rs->num_rows;
    $rpx = $rs->fetch_assoc();
} elseif (isset($_GET["page"]) && !empty($_GET["page"])) {
    $id = (int) $_GET["page"];
    $spg = $conn->prepare("SELECT * FROM page WHERE id = ? AND active = ? ");
    $spg->bind_param("ii", $active, $id);
    $spg->execute();
    $rs = $spg->get_result();
    $spg->close();
    $nm = $rs->num_rows;

    if ($nm > 0) {
        $rpx = $rs->fetch_assoc();
        $namelink = $pages->Pages($rpx["link"]);
        header("Location: $namelink");
        exit();
    } else {
        http_response_code(404);
        header("Location: $pg404");
        exit;
    }
} elseif (isset($basename) && !empty($basename)) {
    $npg = $pages->Pages($basename);
    if ($npg == $url) {
        $spg = $conn->prepare(
            "SELECT * FROM page WHERE link = ? AND active = ? "
        );
        $spg->bind_param("si", $basename, $active);
        $spg->execute();
        $rs = $spg->get_result();
        $spg->close();
        $nm = $rs->num_rows;

        if ($nm > 0) {
            $rpx = $rs->fetch_assoc();
        }else{
            http_response_code(404);
            header("Location: $pg404");
            exit;
        }
    } 
}

if ($nm > 0) {

    $bid = $rpx["id"];
    $title = $rpx["title"];
    $plink = $rpx["link"];
    $keyword = $rpx["keyword"];
    $classification = $rpx["classification"];
    $description = $rpx["description"];
    $cont = $rpx["type"];
    $menu = $rpx["menu"];
    $content = $rpx["content"];
    $style = $rpx["style"];
    $lng = $rpx["language"];

    $visitor->pageViews($title);

    $language = $_SESSION["language"] = $lng;

    require_once "elements/top.php";
    ?>

</head>

<body>
    <div id="wrapper">
        <?php require_once "elements/menu.php"; 
        
        include 'elements/alerts.php';

         if($cont == 'Design'){ 
             $login = new UsersClass();
        ?>
        <div class='container-fluid' id="content-page">
            <?php
                      
                $string = decodeContent($content);
                if (!empty($content)) {
                    $string = str_replace("<body>", "", $string);
                    $string = str_replace("</body>", "", $string);
                }
                echo $string . "\n";
            
            
            ?>
        </div>
        <?php }else{
                $request = $_SERVER['REQUEST_URI'];

                switch ($request) {
                    case '/signin' :
                        require 'pages/login.php';
                        break;
                    case '/signin/login' :
                        require 'pages/login.php';
                        break;
                    case '/signin/register' :
                        require 'pages/register.php';
                        break;
                    case '/signin/forgot-username' :
                        require 'pages/forgot_username.php';
                        break;
                    case '/signin/forgot-email' :
                        require 'pages/forgot_email.php';
                        break;
                    case '/signin/forgot-pin' :
                        require 'pages/forgot_pin.php';
                        break;
                    case '/signin/forgot-password' :
                        require 'pages/forgot_password.php';
                        break;
                    default:
                        http_response_code(404);
                        require $initweb.'404.php';
                        break;
                }
                
            } ?>
    </div>
    <?php require_once "elements/footer.php"; ?>
</body>

</html>
<?php
} else {

    $menu = 1;
    include_once "elements/header.php";
    ?>
</head>

<body>
    <div class="wrapper">
        <h1>Hola</h1>
        <?php require_once "elements/menu.php"; 
        require_once $rpx["path_file"]; ?>
    </div>
    <?php require_once "elements/footer.php"; ?>
</body>

</html>
<?php
}
?>
