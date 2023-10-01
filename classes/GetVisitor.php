<?php

class GetVisitor {

    protected $getip;
    public $baseurl;
    protected $connection;
    private $timestamp;

    public function __construct() {
        global $conn;
        $date = new DateTime();
        $this->timestamp = $date->getTimestamp();
        $this->system = SITE_PATH;
        $this->connection = $conn;

        $this->getip = $this->getUserIP();
        $num = $this->checkUserIP($this->getip);

        if ($num === 0) {
            $stmt = $this->connection->prepare("INSERT INTO visitor (ip) VALUES (?)");
            $stmt->bind_param("s", $this->getip);
            $stmt->execute();

            $stmt1 = $this->connection->prepare("INSERT INTO active_guests (ip) VALUES (?)");
            $stmt1->bind_param("s", $this->getip);
            $stmt1->execute();
        } else {
            $this->CounterVisitor($this->getip);
        }
    }

    public function checkUserIP($ip) {
        $stmt = $this->connection->prepare('SELECT ip FROM visitor WHERE ip = ?');
        $stmt->bind_param('s', $ip);
        $stmt->execute();        
        $num = $stmt->get_result();
        return $num->num_rows;
    }

    public function findUserIP($ip) {
        $stmt = $this->connection->prepare('SELECT * FROM visitor WHERE ip = ? ORDER BY updated DESC LIMIT 0,1');
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        return $stmt->get_result();
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

    public function CounterVisitor($ip) {

        $rest = $this->findUserIP($ip);
        $nums = $rest->num_rows;

        if ($nums > 0) {
            $row = $rest->fetch_assoc();
            $startdate = $row['updated'];
            $enddate = $this->timestamp;
            $dif = $this->differenceInHours($startdate, $enddate);
            if ($dif >= 24) {
                $this->connection->query("UPDATE counter SET counter = counter + 1");
            }
        } else {
            $this->connection->query("UPDATE counter SET counter = counter + 1");
        }
    }

    public function differenceInHours($startdate, $enddate) {
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = round(abs($endtimestamp - $starttimestamp) / 3600, 2);
        return $difference;
    }
}
