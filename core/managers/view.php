<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

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

if ($initweb === $url) {
    $rpx = $pages->InitPage();
} elseif (isset($basename) && !empty($basename)) {
    $npg = $pages->Pages($basename);
    if ($npg == $url) {
        $rpx = $pages->PageDataWeb($basename);
    } else {
        header("Location: $npg");
        exit;
    }
}

$bid = $rpx["id"];
$title = $rpx["title"];
$plink = $rpx["link"];
$purl = $rpx["url"];
$keyword = $rpx["keyword"];
$classification = $rpx["classification"];
$description = $rpx["description"];
$typepage = $rpx["type"];
$menu = $rpx["menu"];
$pfile =$rpx["path_file"];
$content = $rpx["content"];
$style = $rpx["style"];
$lng = $rpx["language"];

$visitor->pageViews($title);

$language = $_SESSION["language"] = $lng;

require_once $source."/core/components/elements/top.php";
?>

</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <div id="wrapper">
        <?php
        require_once $source."/core/components/elements/menu.php";

        if ($typepage == "Design") {
            $login = new UsersClass(); ?>
        <div class='container-fluid' id="content-page">
            <?php
            require_once $source."/core/components/elements/alerts.php";
            $string = decodeContent($content);
            if (!empty($content)) {
                $string = str_replace("<body>", "", $string);
                $string = str_replace("</body>", "", $string);
            }
            echo $string . "\n";
            ?>
        </div>
        <?php
        } else {
            $request = $_SERVER["REQUEST_URI"];

            if($request === $purl){
                // ✅ `$pfile` viene de la BD (`pages.path_file`) y se usaba TAL CUAL en un
                // require_once: un valor como `../../core/config/server` permitía LFI
                // (la página la visita cualquier usuario público).
                // Se valida antes de incluirlo, conservando la resolución original
                // (relativa al directorio de trabajo / include_path) para valores legítimos.
                $pfileSafe = (string) $pfile;

                $pfileValido = $pfileSafe !== ''
                    && !str_contains($pfileSafe, '..')
                    && !str_contains($pfileSafe, "\0")
                    && !preg_match('#(^/|^[A-Za-z]:|://)#', $pfileSafe)      // ni absoluta ni con esquema
                    && preg_match('#^[A-Za-z0-9_\-/\.]+$#', $pfileSafe) === 1; // sólo ruta relativa simple

                if ($pfileValido) {
                    require_once $pfileSafe . ".php";
                } else {
                    error_log('view.php: path_file no permitido, include omitido: ' . $pfileSafe);
                }
            }      
        }
        ?>
    </div>
    <?php require_once $source."/core/components/footer.php"; ?>
</body>
</html>

