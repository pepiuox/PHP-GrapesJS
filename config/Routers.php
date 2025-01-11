<?php

class Routers {

    protected $conn;
    public $url;
    public $host;
    public $basename;
    public $protocol;
    public $escaped_url;
    public $url_path;
    public $active = 1;
    public $startpage = 1;
    public $parent = 0;
    public $pg404;

    public function __construct() {
        global $conn;
        $this->conn = $conn;

        $this->protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
            $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
        $this->host = $this->protocol . $_SERVER["HTTP_HOST"] . "/";
        $this->pg404 = $this->host . "404.php";
        $this->url = $this->protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $this->escaped_url = htmlspecialchars($this->url, ENT_QUOTES, "UTF-8");
        $this->url_path = parse_url($this->escaped_url, PHP_URL_PATH);
        $this->basename = pathinfo($this->url_path, PATHINFO_BASENAME);
    }

    public function Pages($plink) {
        $pg = $this->conn->prepare(
            "SELECT link, parent, active FROM pages WHERE link = ? AND active = ? "
        );
        $pg->bind_param("si", $plink, $this->active);
        $pg->execute();
        $rs = $pg->get_result();
        $pg->close();
        if ($rs->num_rows == 1) {
            $row = $rs->fetch_assoc();
            if ($row["parent"] > 0) {
                $link = $this->GetParent($row["parent"]);
                return $this->host . $link . "/" . $row["link"];
            } else {
                return $this->host . $row["link"];
            }
        } else {
            header("Location: $this->pg404");
            die();
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

    private function SplitUrl() {
        $host = explode("/", substr($_SERVER["REQUEST_URI"], 1));
        $extension = pathinfo($host[0], PATHINFO_EXTENSION);
        if ($extension == true) {
            return pathinfo($host[0], PATHINFO_FILENAME);
        } else {
            return $host[0];
        }
    }

    public function ExistsPage() {
        return $_SERVER["REQUEST_URI"];
    }

    public function GoPage() {
        $page = $this->basename;
        if ($page === "home" || $page === "inicio" || empty($page)) {
            return true;
        } else {
            $spg = $this->conn->prepare("SELECT * FROM pages WHERE link = ? AND active = ? ");
            $spg->bind_param("si", $page, $this->active);
            $spg->execute();
            $rs = $spg->get_result();
            $nm = $rs->num_rows;
            if ($nm > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function routePages() {

        $nm = "";
        $page = $this->basename;
        if ($page === "home" || $page === "inicio") {
            header("Location: $this->host");
            die();
        } else if (isset($_GET['url']) && !empty($_GET['url'])) {
            $id = (int) $_GET['url'];
            $spg = $this->conn->prepare("SELECT * FROM pages WHERE id = ? AND active = ? ");
            $spg->bind_param("ii", $id, $this->active);
            $spg->execute();
            $rs = $spg->get_result();
            $nm = $rs->num_rows;
            if ($nm > 0) {
                $rpx = $rs->fetch_assoc();
                $link = $this->Pages($rpx['link']);
                header("Location: $link");
                die();
            } else {
                header("Location: $this->host");
                die();
            }
        } else {
            return;
        }
    }

    public function ContentPage() {
        $page = $this->basename;
        $nm = "";
        if ($this->host === $this->url) {
            $spg = $this->conn->prepare("SELECT * FROM pages WHERE startpage = ? AND active = ? ");
            $spg->bind_param("ii", $this->startpage, $this->active);
            $spg->execute();
            $rs = $spg->get_result();
            $nm = $rs->num_rows;
            return $rs->fetch_assoc();
        } elseif ($page === $this->basename) {
            $spg = $this->conn->prepare("SELECT * FROM pages WHERE link = ? AND active = ? ");
            $spg->bind_param("si", $this->basename, $this->active);
            $spg->execute();
            $rs = $spg->get_result();
            $spg->close();
            $nm = $rs->num_rows;

            if ($nm > 0) {
                return $rs->fetch_assoc();
            } else {
                header("Location: $this->host");
                die();
            }
        } else {
            header("Location: $this->host");
            die();
        }
    }
}
