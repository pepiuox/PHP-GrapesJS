<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
//  Description of Database class
//  Database.php file
//
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
        $settings = '';
        require_once 'server.php';
        $this->config = $settings;
        $this->socket = "";
    }

    public function getConnection() {
        $this->conn = null;
        $default = $this->config['default-connection'];
        $data = $this->config["connections"][$default];

        $this->host = $data['server'];
        $this->dbnm = $data['database'];
        $this->user = $data['username'];
        $this->pass = $data['password'];
        $this->port = $data['port'];
        $this->charset = $data['charset'];
        $this->dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbnm . ";charset=" . $this->charset . ";port=" . $this->port;

        try {
            $this->conn = new PDO($this->dsn, $this->user, $this->pass, $this->options);

            // Configurar PDO para lanzar excepciones
            $this->conn->exec("set names utf8");
            // Set timezone
            $this->conn->exec("SET time_zone = '+00:00'");
        } catch (PDOException $exception) {
            // Registrar error y mostrar mensaje apropiado
            error_log("Error de conexión a la base de datos: " . $exception->getMessage());

            if (DEBUG) {
                die("Error de conexión: " . $exception->getMessage());
            } else {
                die("Error de conexión a la base de datos. Por favor, intente más tarde.");
            }
        }

        return $this->conn;
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

    // Método para probar la conexión
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            return $conn !== null;
        } catch (Exception $e) {
            return false;
        }
    }

    // Método para obtener información de la base de datos
    public function getDatabaseInfo() {
        try {
            $conn = $this->getConnection();
            $info = [];

            // Versión de MySQL
            $stmt = $conn->query("SELECT VERSION() as version");
            $info['version'] = $stmt->fetchColumn();

            // Características
            $stmt = $conn->query("SHOW VARIABLES LIKE 'character_set_database'");
            $info['charset'] = $stmt->fetchColumn(1);

            $stmt = $conn->query("SHOW VARIABLES LIKE 'collation_database'");
            $info['collation'] = $stmt->fetchColumn(1);

            return $info;
        } catch (PDOException $e) {
            error_log("Error obteniendo información de la base de datos: " . $e->getMessage());
            return [];
        }
    }

    public function getPageContent($id) {

        try {
            $stmt = $this->conn->prepare("SELECT id, title, slug, content_css, content_html, status FROM pages WHERE id = ?");
            $stmt->execute([$id]);
            $page = $stmt->fetch();

            if (!$page) {
                return '<div class="container"><h1>Página no encontrada</h1></div>';
            } else {
                // Devolver el HTML directamente (GrapesJS lo parseará automáticamente)
                return $page;
            }
        } catch (PDOException $e) {
            error_log("Error al cargar página: " . $e->getMessage());
            return '<div class="container"><h1>Error al cargar la página</h1></div>';
        }
    }

    public function select($query = "", $params = []) {

        try {

            $stmt = $this->executeStatement($query, $params);

            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $result;
        } catch (Exception $e) {

            throw New Exception($e->getMessage());
        }

        return false;
    }

    private function executeStatement($query = "", $params = []) {

        try {

            $stmt = $this->connection->prepare($query);

            if ($stmt === false) {

                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if ($params) {

                $stmt->bind_param($params[0], $params[1]);
            }

            $stmt->execute();

            return $stmt;
        } catch (Exception $e) {

            throw New Exception($e->getMessage());
        }
    }
}
