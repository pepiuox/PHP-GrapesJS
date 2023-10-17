<?php

class GetVisitor {

    protected $getip;
    public $baseurl;
    protected $connection;
    private $timestamp;
    public $date;
    protected $hash;
    protected $token;
    private $session;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $this->date = new DateTime();
        $this->timestamp = $this->date->format('Y-m-d H:i:s');
        $this->system = SITE_PATH;
        $this->hash = SECURE_HASH;
        $this->token = SECURE_TOKEN;
        $this->getip = $this->getUserIP();

        $_SESSION['session_visit'] = $this->ende_crypter('encrypt', $this->getip, $this->hash, $this->token);
        $this->session = $_SESSION['session_visit'];

        $this->VisitUpdate($this->getip);

        if (!empty($this->session)) {
            $this->guest_online();
        }
    }

    /* get number of pages
     * 
     */

    public function numpages() {

        return $this->connection->query("SELECT id FROM page")->num_rows;
    }

    /* get number of visitor
     * 
     */

    public function numvisitor() {

        return $this->connection->query("SELECT ip FROM active_guests")->num_rows;
    }

    /* get number of users
     * 
     */

    public function numusers() {

        return $this->connection->query("SELECT verified FROM users WHERE verified='1'")->num_rows;
    }

    private function ende_crypter($action, $string, $secret_key, $secret_iv) {
        $output = false;
        $encrypt_method = 'AES-256-CBC';
// hash
        $key = hash('sha256', $secret_key);
// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public function checkUserIP($ip) {
        $num = $this->findUserIP($ip);
        return $num->num_rows;
    }

    public function findUserIP($ip) {
        $stmt = $this->connection->prepare('SELECT * FROM visitor WHERE ip = ? ORDER BY updated DESC LIMIT 0,1');
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function VisitUpdate($ip) {
        $rest = $this->findUserIP($ip);
        $nums = $rest->num_rows;
        if ($nums > 0) {
            $row = $rest->fetch_assoc();
            $startdate = $row['updated'];
            $enddate = $this->timestamp;
            $dif = $this->differenceInHours($startdate, $enddate);
            if ($dif >= 24) {
                $stmt = $this->connection->prepare("INSERT INTO visitor (ip) VALUES (?)");
                $stmt->bind_param("s", $ip);
                $stmt->execute();

                $this->CounterVisitor();
            } else {
                $stmt = $this->connection->prepare("UPDATE visitor SET updated = ? WHERE ip = ? AND updated = ?");
                $stmt->bind_param('sss', $enddate, $ip, $startdate);
                $stmt->execute();
            }
        } else {
            $stmt = $this->connection->prepare("INSERT INTO visitor (ip) VALUES (?)");
            $stmt->bind_param("s", $ip);
            $stmt->execute();

            $stmt1 = $this->connection->prepare("INSERT INTO active_guests (ip) VALUES (?)");
            $stmt1->bind_param("s", $ip);
            $stmt1->execute();

            $this->CounterVisitor();
        }
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

    public function CounterVisitor() {
        $this->connection->query("UPDATE counter SET counter = counter + 1");
    }

// Calculate the time between two hours
    public function differenceInHours($startdate, $enddate) {
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = round(abs($endtimestamp - $starttimestamp) / 3600, 2);
        return $difference;
    }

// Insert the IP and title of the page visited during the day
    public function pageViews($title) {

        $stmt = $this->connection->prepare('SELECT * FROM pageviews WHERE page = ? AND ip = ? ORDER BY date_view DESC LIMIT 0,1');
        $stmt->bind_param('ss', $title, $this->getip);
        $stmt->execute();
        $rows = $stmt->get_result();
        if ($rows->num_rows > 0) {
            $row = $rows->fetch_assoc();
            $startdate = $row['date_view'];
            $enddate = $this->timestamp;
            $dif = $this->differenceInHours($startdate, $enddate);
            if ($dif >= 24) {
                $stmt = $this->connection->prepare("INSERT INTO pageviews (page,ip) VALUES (?,?)");
                $stmt->bind_param('ss', $title, $this->getip);
                $stmt->execute();
            }
        } else {
            $stmt = $this->connection->prepare("INSERT INTO pageviews (page,ip) VALUES (?,?)");
            $stmt->bind_param('ss', $title, $this->getip);
            $stmt->execute();
        }
    }

    public function guest_online() {

        $current_time = $this->timestamp;

        $stmt = $this->connection->prepare("SELECT session FROM total_visitors WHERE session = ?");
        $stmt->bind_param('s', $this->session);
        $stmt->execute();
        $session_exist = $stmt->get_result();
        $session_check = $session_exist->num_rows;

        if ($session_check == 0 && $this->session != "") {
            $stmt = $this->connection->prepare("INSERT INTO total_visitors (session, time) VALUES (?,?)");
            $stmt->bind_param('ss', $this->session, $current_time);
            $stmt->execute();
        } else {
            $stmt = $this->connection->prepare("UPDATE total_visitors SET time = ? WHERE session = ?");
            $stmt->bind_param('ss', $current_time, $this->session);
            $stmt->execute();
        }
    }

    public function total_online() {

        $time = strtotime($this->timestamp);
        $tim = $time - (60 * 60); //one hour
        $timeout = date("Y-m-d H:i:s", $tim);

        $stmt = $this->connection->prepare("SELECT * FROM total_visitors WHERE time>= ?");
        $stmt->bind_param('s', $timeout);
        $stmt->execute();
        $select_total = $stmt->get_result();
        return $select_total->num_rows;
    }
}
