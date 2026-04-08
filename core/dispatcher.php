<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author : PePiuoX
//  Email  : contact@pepiuox.net
// This file dispatch all file content
//

require_once "config/loader.php";

$_SESSION["URL"] = URL;
$menu = '';
$title = '';
$pages = new Routers();
$visitor = new GetVisitor();
$viewp = new PagePublic();
$views = new PageSystem();
$pages->loadPage();
$pages->routePages();
$rp = $pages->InitPage();


$request = $_SERVER["REQUEST_URI"];
if ($rp != null) {
    if ($rp['view_page'] === "public") {
        $title = $rp['title'];
        $lng = $rp['language'];
        $visitor->pageViews($title);
        $language = $_SESSION["language"] = $lng;
        $viewp->viewPagePublic($pages->InitPage());
    }
} else {
    $views->viewPageSystem();
}

