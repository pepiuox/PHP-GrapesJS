<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author : PePiuoX
//  Email  : contact@pepiuox.net
// This file PageSystem all show page of the system
//

class PageSystem {

    protected $conn;
    public $tempURL;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->tempURL = explode('/', $_SERVER['REQUEST_URI']);
    }

    public function viewPageSystem() {
        global $fname, $lang;

        $tempBASE = $this->tempURL[1];
        $tempURI = $this->tempURL[2];

        if (!empty($this->tempURL[3])) {
            define('CMS', $this->tempURL[3]);
        }
        if (!empty($this->tempURL[4])) {

            if (is_numeric($this->tempURL[4]) === TRUE) {
                define('IDP', $this->tempURL[4]);
            } else {
                define('WS', $this->tempURL[4]);
            }
        }
        if (!empty($this->tempURL[5])) {
            if (is_numeric($this->tempURL[5]) === TRUE) {
                define('IDP', $this->tempURL[5]);
            } else {
                define('TBL', $this->tempURL[5]);
            }
        }
        if (!empty($this->tempURL[6])) {
            define('IDP', $this->tempURL[6]);
        }

        if ($tempBASE === "signin") {
            include_once URL . "/core/elements/top.php";
            ?>
            </head>
            <body>
            <div id="wrapper">
            <div class='container-fluid min-h-screen' id="content-page">
            <?php
            include_once URL . "/core/elements/menu.php";
            if ($tempURI === "login") {
                include_once URL . "/core/pages/login/login.php";
            } else if ($tempURI === "logout") {
                include_once URL . "/core/pages/login/logout.php";
            } else if ($tempURI === "register") {
                include_once URL . "/core/pages/register/register.php";
            } else if ($tempURI === "forgot-username") {
                include_once URL . "/core/pages/forgot/forgot-username.php";
            } else if ($tempURI === "forgot-password") {
                include_once URL . "/core/pages/forgot/forgot-password.php";
            } else if ($tempURI === "forgot-email") {
                include_once URL . "/core/pages/forgot/forgot-email.php";
            } else if ($tempURI === "forgot-pin") {
                include_once URL . "/core/pages/forgot/forgot-pin.php";
            }
            ?>
            </div>
            <?php include_once URL . "/core/elements/footer.php"; ?>
            </div>
            </body>
            </html>
            <?php
        } else if ($tempBASE === "admin") {

            if ($tempURI === "dashboard") {
                include_once URL . "/core/elements/header_dashboard.php";
            ?>
            </head>
            <body class="hold-transition sidebar-mini">
            <div class="wrapper">
                <?php
                include_once URL . "/core/managers/dashboard.php";
                ?>
            </div>
            </body>
            </html>
                <?php
            }

            if ($tempURI === "builder") {
                include URL . "/core/elements/top_build.php";
                ?>
            </head>
            <body id="builder">
                <?php
                if (!empty($this->tempURL[3])) {
                    define('PAG', $this->tempURL[3]);
                }

                include_once URL . "/core/managers/builder.php";
                ?>
            </body>
            </html>
                <?php
            }

        } else if ($tempBASE === "profile") {
            include URL . "/core/elements/header.php";
            ?>
            </head>
            <body class="hold-transition sidebar-mini">
            <div class="wrapper">
            <?php
            if (!empty($this->tempURL[3])) {
                define('USR', $this->tempURL[3]);
            }
            if (!empty($this->tempURL[4])) {
                define('WS', $this->tempURL[4]);
            }
            include_once URL . "/core/users/" . $tempURI . ".php";
            ?>
            </div>
            </body>
            </html>
            <?php
        }else if ($tempBASE === "error") {
            include_once URL . "/core/elements/top.php";
            ?>
            </head>
            <body>
            <div id="wrapper">
            <div class='container-fluid min-h-screen' id="content-page">
            <?php
            include_once URL . "/core/elements/menu.php";
            include_once URL . "/core/pages/error_pages/404.php";
            ?>
            </div>
            <?php include_once URL . "/core/elements/footer.php"; ?>
            </div>
            </body>
            </html>
            <?php
        }


    }
}
