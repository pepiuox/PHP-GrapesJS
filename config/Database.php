<?php

class Database {

    private $config;
    private $host;
    private $dbnm;
    private $user;
    private $pass;
    private $port;
    private $socket;
    private $charset;
    private $conn;
    private $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        \PDO::MYSQL_ATTR_FOUND_ROWS => true,
        \PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
    ];
    private $db;
    private $dsn;

    public function __construct() {
        include_once 'server.php';
        $this->config = $settings;
        $this->socket = "";
    }

    //get the db mysqli connection
    public function MysqliConnection($default) {
        
        $data = $this->config["connections"][$default];

        $this->host = $data['server'];
        $this->dbnm = $data['database'];
        $this->user = $data['username'];
        $this->pass = $data['password'];
        $this->port = $data['port'];
        $this->charset = $data['charset'];

        define('DBHOST', $this->host);
        define('DBUSER', $this->user);
        define('DBPASS', $this->pass);
        define('DBNAME', $this->dbnm);

        mysqli_report(MYSQLI_REPORT_OFF);
        $this->conn = @new mysqli($this->host, $this->user, $this->pass, $this->dbnm, $this->port, $this->socket);

        /* If connection fails for some reason */
        if ($this->conn->connect_error) {
            die('Error, Database connection failed: (' . $this->conn->connect_errno . ') ' . $this->conn->connect_error);
            exit();
        }
        $this->conn->set_charset($this->charset);
        return $this->conn;
    }

//get the db pdo connection    
    public function PdoConnection($default) {

        $data = $this->config["connections"][$default];

        $this->host = $data['server'];
        $this->dbnm = $data['database'];
        $this->user = $data['username'];
        $this->pass = $data['password'];
        $this->port = $data['port'];
        $this->charset = $data['charset'];

        if (is_array($this->config['connections'])) {
            $this->dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbnm . ";charset=" . $this->charset . ";port=" . $this->port;

            try {
                $this->db = new PDO($this->dsn, $this->user, $this->pass, $this->options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int) $e->getCode());
            }
            return $this->db;
        }
    }
}
