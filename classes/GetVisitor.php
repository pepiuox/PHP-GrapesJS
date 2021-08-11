<?php

class GetVisitor {

    public $getip;
    public $baseurl;
    public $connection;

    public function __construct($timestamp) {
        global $conn, $base;
        $this->system = $base;
        $this->connection = $conn;
        $this->getip = $this->getUserIP();
        $num = $this->checkUserIP($this->getip);
        if ($num === 0) {
            $stmt = $this->connection->prepare("INSERT INTO visitor (ip, timestamp) VALUES (?, ?)");
            $stmt->bind_param("ss", $this->getip, $timestamp);
            $stmt->execute();

            $stmt1 = $this->connection->prepare("INSERT INTO active_guests (ip, timestamp) VALUES (?, ?)");
            $stmt1->bind_param("ss", $this->getip, $timestamp);
            $stmt1->execute();
        }
    }

    public function checkUserIP($ip) {

        $stmt = $this->connection->prepare('SELECT ip FROM visitor WHERE ip = ?');
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $num = $stmt->get_result();
        return $num->num_rows;
    }

    public function getUserIP() {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    public function Attempts() {
        $this->connection->query("INSERT INTO `ip` (`address` ,`timestamp`)VALUES ('$this->getip',CURRENT_TIMESTAMP)");
        $result = $this->connection->query("SELECT COUNT(*) FROM `ip` WHERE `address` LIKE '$this->getip' AND `timestamp` > (now() - interval 10 minute)");
        $count = $result->fetch_array(MYSQLI_NUM);

        if ($count[0] > 3) {
            echo "Your are allowed 3 attempts in 10 minutes";
        }
    }

}
