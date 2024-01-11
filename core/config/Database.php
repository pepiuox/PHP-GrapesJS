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
    protected $conn;
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
        $settings ='';
        require_once 'server.php';
        $this->config = $settings;
        $this->socket = "";
    }

    public function PdoConnection($db = '') {

        if (empty($db)) {
            $default = $this->config['default-connection'];
        } else {
            $default = $db;
        }

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

//get the db connection
    public function MysqliConnection($db = '') {
        if (empty($db)) {
            $default = $this->config['default-connection'];
        } else {
            $default = $db;
        }

        $data = $this->config["connections"][$default];

        $this->host = $data['server'];
        $this->dbnm = $data['database'];
        $this->user = $data['username'];
        $this->pass = $data['password'];
        $this->port = $data['port'];
        $this->charset = $data['charset'];

        $this->conn = @new mysqli($this->host, $this->user, $this->pass, $this->dbnm, $this->port, $this->socket);

        /* If connection fails for some reason */
        if ($this->conn->connect_error) {
            die('Error, Database connection failed: (' . $this->conn->connect_errno . ') ' . $this->conn->connect_error);
        }
        $this->conn->set_charset($this->charset);
        return $this->conn;
    }
}
