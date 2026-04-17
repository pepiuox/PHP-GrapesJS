<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
//  Description of Routers class
//  Routers.php file
//
class Routers {

    protected $conn;
    public $url;
    public $host;
    public $basepage;
    public $protocol;
    public $escaped_url;
    public $url_path;
    public $startpage = 1;
    public $active = 1;
    public $parent = 0;
    public $pg404;
    private $columns;

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
        $this->basepage = pathinfo($this->url_path, PATHINFO_BASENAME);
        $this->columns = ['id',
            'language',
            'view_page',
            'title',
            'slug',
            'link',
            'url',
            'keyword',
            'classification',
            'description',
            'type',
            'menu',
            'path_file',
            'html_content',
            'css_content',
            'php_content',
            'js_content',
            'parent'];
    }

    public function loadPage() {
        if ($this->host === $this->url) {
            $rpx = $this->InitPage();
        } elseif (isset($this->basepage) && !empty($this->basepage)) {

            $npg = $this->Pages();
            if ($npg == $this->url) {
                $rpx = $this->PageDataWeb();
            } else {
                header("Location: $npg");
                die();
            }
        } else {
            return $this->routePages();
        }
    }

    public function InitPage() {
        if ($this->host === $this->url) {
            $stmt = $this->conn->prepare(
                    "SELECT * FROM pages WHERE startpage = ? AND active = ? "
            );
            $stmt->bind_param("ii", $this->startpage, $this->active);
            $stmt->execute();
            $rs = $stmt->get_result();
            $stmt->close();

            if ($rs->num_rows === 1) {
                return $rs->fetch_assoc();
            }
        } else {
            return $this->PageDataWeb();
        }
    }

    public function PageDataWeb() {
        $stmt = $this->conn->prepare(
                "SELECT * FROM pages WHERE link = ? AND active = ? "
        );
        $stmt->bind_param("si", $this->basepage, $this->active);
        $stmt->execute();
        $rs = $stmt->get_result();
        $stmt->close();
        $nm = $rs->num_rows;

        if ($nm > 0) {
            return $rs->fetch_assoc();
        } else {
            return null;
        }
    }

    public function GoPage() {
        $page = $this->basepage;
        if ($page === "home" || $page === "inicio" || empty($page)) {
            return true;
        } else {

            $spg = $this->conn->prepare("SELECT link, active  FROM pages WHERE link = ? AND active = ? ");
            $spg->bind_param("si", $page, $this->active);
            $spg->execute();
            $rs = $spg->get_result();
            $nm = $rs->num_rows;
            if ($nm === 1) {
                return true;
            } else {
                return false;
            }
        }
    }
// This functtion returns
    public function routePages() {
        $nm = "";

        if ($this->basepage === "home" || $this->basepage === "inicio") {
            return $this->host;
        } else if (isset($_GET['url']) && !empty($_GET['url'])) {
            $id = (int) $_GET['url'];
            $spg = $this->conn->prepare("SELECT * FROM pages WHERE id = ? AND active = ? ");
            $spg->bind_param("ii", $id, $this->active);
            $spg->execute();
            $rs = $spg->get_result();
            $nm = $rs->num_rows;
            if ($nm > 0) {
                $rpx = $rs->fetch_assoc();
                return $this->Pages($rpx['link']);
            } else {
               return false;
            }
        } else {
            return false;
        }
    }

    public function Pages() {
        $pg = $this->conn->prepare(
                "SELECT link, startpage, parent, active FROM pages WHERE link = ? AND active = ? "
        );
        $pg->bind_param("si", $this->basepage, $this->active);
        $pg->execute();
        $rs = $pg->get_result();
        $pg->close();
        if ($rs->num_rows == 1) {
            $row = $rs->fetch_assoc();
            if ($row["startpage"] === 1) {
                return $this->host;
            }
            if ($row["parent"] > 0) {
                $link = $this->GetParent($row["parent"]);
                return $this->host . $link . "/" . $row["link"];
            } else {
                return $this->host . $row["link"];
            }
        } else {
            return $this->pg404;
        }
    }

    public function GetParent($parent) {
        $pr = $this->conn->prepare(
                "SELECT id, link, parent, active FROM pages WHERE id = ? AND active = ? "
        );
        $pr->bind_param("ii", $parent, $this->active);
        $pr->execute();
        $rp = $pr->get_result();
        $pr->close();
        $row = $rp->fetch_assoc();
        if ($row["parent"] > 0) {
            $link = $this->GetSecondParent($row["parent"]);
            return $link . "/" . $row["link"];
        } else {
            return $row["link"];
        }
    }

    public function GetSecondParent($parent) {
        $pr = $this->conn->prepare(
                "SELECT id, link, parent, active FROM pages WHERE id = ? AND active = ? "
        );
        $pr->bind_param("ii", $parent, $this->active);
        $pr->execute();
        $rp = $pr->get_result();
        $pr->close();
        $row = $rp->fetch_assoc();
        if ($row["parent"] > 0) {
            $link = $this->GetThirdParent($row["parent"]);
            return $link . "/" . $row["link"];
        } else {
            return $row["link"];
        }
    }

    public function GetThirdParent($parent) {
        $pr = $this->conn->prepare(
                "SELECT id, link, parent, active FROM pages WHERE id = ? AND active = ? "
        );
        $pr->bind_param("ii", $parent, $this->active);
        $pr->execute();
        $rp = $pr->get_result();
        $pr->close();
        $row = $rp->fetch_assoc();
        return $row["link"];
    }
}
