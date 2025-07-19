<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
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
