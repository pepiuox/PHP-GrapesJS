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

// ✅ Inyección de dependencias: todas estas clases exigen la conexión PDO en su
// constructor. Antes se instanciaban sin argumentos → ArgumentCountError (fatal).
$pages = new Routers($conn);
$visitor = new GetVisitor($conn);
$viewP = new PagePublic($conn);
$viewS = new PageSystem($conn);

$rp = $pages->InitPage();


$request = $_SERVER["REQUEST_URI"];
// Show pages in data base for view public or front end
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
// Show pages of back end or manager system
    if($pages->routePages() == false){
        $viewS->viewPageSystem();
        $title = $pages->GetTitle();
        $visitor->pageViews($title);
    }
}
