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
class Routers
{
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

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;

        $this->protocol =
            (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
            $_SERVER["SERVER_PORT"] == 443
                ? "https://"
                : "http://";
        $this->host = $this->protocol . $_SERVER["HTTP_HOST"] . "/";
        $this->pg404 = $this->host . "error/404";
        $this->url =
            $this->protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $this->escaped_url = htmlspecialchars($this->url, ENT_QUOTES, "UTF-8");
        $this->url_path = parse_url($this->escaped_url, PHP_URL_PATH);
        $this->basename = pathinfo($this->url_path, PATHINFO_BASENAME);
    }

    public function InitPage()
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM pages WHERE startpage = ? AND active = ? "
        );
        $stmt->bind_param("ii", $this->startpage, $this->active);
        $stmt->execute();
        $rs = $stmt->get_result();
        $stmt->close();

        if ($rs->num_rows == 1) {
            return $rs->fetch_assoc();
        }
    }

    public function PageDataWeb($basename)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM pages WHERE link = ? AND active = ? "
        );
        $stmt->bind_param("si", $basename, $this->active);
        $stmt->execute();
        $rs = $stmt->get_result();
        $stmt->close();
        $nm = $rs->num_rows;

        if ($nm === 1) {
            return $rs->fetch_assoc();
        } else {
            return null;
        }
    }

    public function Pages($plink)
    {
        $pg = $this->conn->prepare(
            "SELECT system_path, link, startpage, type, path_file, parent, active FROM pages WHERE link = ? AND active = ? "
        );
        $pg->bind_param("si", $plink, $this->active);
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
                if (empty($row["system_path"])) {
                    return $this->host . $row["link"];
                } else {
                    return $this->host . $row["system_path"] . $row["link"];
                }
            }
        } else {
            return $this->pg404;
        }
    }

    public function GetParent($parent)
    {
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

    public function GetSecondParent($parent)
    {
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

    public function GetThirdParent($parent)
    {
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
