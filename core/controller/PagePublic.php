<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author : PePiuoX
//  Email  : contact@pepiuox.net
// This file PageModels all show page content
//

class PagePublic {

    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function viewPagePublic($rp) {
        global $fname, $title;

        include_once URL . "/core/elements/top.php";

        if ($rp['type'] === 'Design') {
            echo '<style>' . "\n";
            echo decodeContent($rp['css_content']) . "\n";
            echo '</style>' . "\n";
        }
?>
                    </head>
                    <body>
                    <div id="wrapper">
                    <div class='container-fluid min-h-screen' id="content-page">
        <?php
        include_once URL . "/core/elements/menu.php";
        if ($rp['type'] === 'File') {
            include URL . "/core/elements/alerts.php";

            if ($_SERVER["REQUEST_URI"] === $rp['url']) {
                include_once $rp['path_file'] . ".php";
            }
        } else if ($rp['type'] === 'Design') {
            $string = decodeContent($rp['html_content']);
            if (!empty($rp['html_conten'])) {
                $string = str_replace("<body>", "", $string);
                $string = str_replace("</body>", "", $string);
            }
            echo $string . "\n";
        }
        ?>
                    </div>
        <?php include_once URL . "/core/elements/footer.php"; ?>
                    </div>
                    </body>
                    </html>
        <?php
    }
}
