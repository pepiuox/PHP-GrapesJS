<?php
class Routers
{
    protected $conn;
    public $url;
    public $host;
    public $basename;
    public $protocol;
    public $escaped_url;
    public $url_path;
    public $active = 1;
    public $parent = 0;
    public $pg404;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;

        $this->protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
        $_SERVER["SERVER_PORT"] == 443
            ? "https://"
            : "http://";
        $this->host = $this->protocol . $_SERVER["HTTP_HOST"] . "/";
        $this->pg404 = $this->host . "404.php";
        $this->url =
            $this->protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $this->escaped_url = htmlspecialchars($this->url, ENT_QUOTES, "UTF-8");
        $this->url_path = parse_url($this->escaped_url, PHP_URL_PATH);
        $this->basename = pathinfo($this->url_path, PATHINFO_BASENAME);
    }

    public function Pages($plink)
    {
        $pg = $this->conn->prepare(
            "SELECT link, startpage, parent, type, path_file, active FROM page WHERE link = ? AND active = ? "
        );
        $pg->bind_param("si", $plink, $this->active);
        $pg->execute();
        $rs = $pg->get_result();
        $pg->close();
        if ($rs->num_rows == 1) {
            $row = $rs->fetch_assoc();
            if($row["startpage"] === 1){
                       return $this->host; 
                    }
            if($row["type"] === 'Design'){
                if ($row["parent"] > 0) {
                    $link = $this->GetParent($row["parent"]);
                    return $this->host . $link . "/" . $row["link"];
                } else {
                   return $this->host . $row["link"];
                }
            } else {
                  return $this->host . $row["path_file"];  
            }       
        }
    }

    public function GetParent($parent)
    {
        $pr = $this->conn->prepare(
            "SELECT id, link, parent, active FROM page WHERE id = ? AND active = ? "
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
            "SELECT id, link, parent, active FROM page WHERE id = ? AND active = ? "
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
            "SELECT id, link, parent, active FROM page WHERE id = ? AND active = ? "
        );
        $pr->bind_param("ii", $parent, $this->active);
        $pr->execute();
        $rp = $pr->get_result();
        $pr->close();
        $row = $rp->fetch_assoc();
        return $row["link"];
    }

}
