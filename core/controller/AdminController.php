<?php

// Example Controllers (Simplified for demonstration)

class AdminController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function dashboard() {
        // Display admin dashboard
        echo "Welcome to Admin Dashboard";
    }
}
