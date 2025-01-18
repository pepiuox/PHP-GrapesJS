<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
//  Description of Routers class
//  Paths.php file
//
class Paths {

    protected $conn;
    public $url;
    public $host;
    public $basename;
    public $protocol;
    public $escaped_url;
    public $url_path;
    public $startpage = 1;
    public $active = 1;
    public $parent = 0;
    public $pg404;

    public function __construct() {
        global $conn;
        $this->conn = $conn;

        $this->protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
            $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
        $this->host = $this->protocol . $_SERVER["HTTP_HOST"] . "/";
        $this->pg404 = $this->host . "error/404";
        $this->url = $this->protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $this->escaped_url = htmlspecialchars($this->url, ENT_QUOTES, "UTF-8");
        $this->url_path = parse_url($this->escaped_url, PHP_URL_PATH);
        $this->basename = pathinfo($this->url_path, PATHINFO_BASENAME);
    }

    public function SystemPath($plink) {
        $pg = $this->conn->prepare(
            "SELECT system_path, link, startpage, type, path_file, parent, active FROM pages WHERE link = ? AND active = ? "
        );
        $pg->bind_param("si", $plink, $this->active);
        $pg->execute();
        $rs = $pg->get_result();
        $pg->close();
        if ($rs->num_rows == 1) {
            $row = $rs->fetch_assoc();

            if (!empty($row["system_path"])) {
                return $this->host . $row["system_path"] . $row["link"];
            } else {

                return $this->host . $row["link"];
            }
        }
    }
}

?>
