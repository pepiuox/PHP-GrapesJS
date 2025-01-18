<?php

class SecurityAccess {
    private $dbHost = 'your_host';
    private $dbName = 'your_database';
    private $dbUser = 'your_username';
    private $dbPass = 'your_password';
    private $userSessionKey = 'securedUserSession';
    private $conn;

    public function __construct() {
        $this->connectDB();
    }

    private function connectDB() {
        try {
            $this->conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Register a new user
    public function registerUser($username, $email, $password, $role = 'user') {
        try {
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            // **For Production, use password_hash() instead of md5()**
            $stmt->bindParam(':password', md5($password));
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return "Username or Email already exists.";
            }
            return "Registration failed: " . $e->getMessage();
        }
    }

    // Login user and start session
    public function loginUser($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
            $stmt->bindParam(':username', $username);
            // **For Production, use password_verify() with password_hash()**
            $stmt->bindParam(':password', md5($password));
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $_SESSION[$this->userSessionKey] = $user;
                return true;
            } else {
                return "Invalid username or password.";
            }
        } catch (PDOException $e) {
            return "Login failed: " . $e->getMessage();
        }
    }

    // Check if user is logged in
    public function isUserLoggedIn() {
        return isset($_SESSION[$this->userSessionKey]);
    }

    // Get logged in user's details
    public function getLoggedInUser() {
        return $this->isUserLoggedIn() ? $_SESSION[$this->userSessionKey] : null;
    }

    // Logout user
    public function logoutUser() {
        if ($this->isUserLoggedIn()) {
            unset($_SESSION[$this->userSessionKey]);
            session_destroy();
            return true;
        }
        return false;
    }

    // Example Role-Based Access Control (RBAC)
    public function hasAccess($requiredRole) {
        $loggedInUser = $this->getLoggedInUser();
        if ($loggedInUser && in_array($loggedInUser['role'], [$requiredRole, 'admin'])) {
            return true;
        }
        return false;
    }
}

// Example Usage
$access = new SecurityAccess();

// Register
if (isset($_POST['register'])) {
    $result = $access->registerUser($_POST['username'], $_POST['email'], $_POST['password']);
    echo $result;
}

// Login
if (isset($_POST['login'])) {
    $result = $access->loginUser($_POST['username'], $_POST['password']);
    if ($result === true) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo $result;
    }
}

// Access Control Example
if ($access->hasAccess('admin')) {
    echo "You have admin access.";
} else {
    echo "You do not have admin access.";
}

// Logout
if (isset($_GET['logout'])) {
    if ($access->logoutUser()) {
        header('Location: index.php');
        exit;
    }
}

