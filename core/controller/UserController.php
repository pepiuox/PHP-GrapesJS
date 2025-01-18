<?php

class UserController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function profile() {
        // Display user profile
        echo "Welcome to Your Profile";
    }
}
