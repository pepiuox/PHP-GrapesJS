<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
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
