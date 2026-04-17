<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author : PePiuoX
//  Email  : contact@pepiuox.net
//  This file dispatch all file content
//

require_once "config/loader.php";

$_SESSION["URL"] = URL;
$menu = '';
$title = '';
$pages = new Routers();
$visitor = new GetVisitor();
$viewP = new PagePublic();
$viewS = new PageSystem();

$rp = $pages->InitPage();


$request = $_SERVER["REQUEST_URI"];
if ($pages->GoPage() === true) {
    if ($rp != null) {
        if ($rp['view_page'] === "public") {
            $title = $rp['title'];
            $lng = $rp['language'];
            $visitor->pageViews($title);
            $language = $_SESSION["language"] = $lng;
            $viewP->viewPagePublic($pages->InitPage());
        }
    }
} else {
    if($pages->routePages() == false){
        $viewS->viewPageSystem();
    }
}
