<?php

class Protect
{
    protected $conn;
    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }
    public function secureStr($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES);
    }
    public function protectStr($str)
    {
        if (!empty($str)) {
            $str = trim($str);
            $str = stripslashes($str);
            $str = htmlentities($str, ENT_QUOTES);
            $str = htmlspecialchars($str, ENT_QUOTES);
            $str = mysqli_real_escape_string($this->conn, $str);
            return $str;
        } else {
            return null;
        }
    }
}
