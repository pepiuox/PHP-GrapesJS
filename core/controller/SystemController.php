<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class SystemController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function config() {
        // Handle with extreme care - System Config
        echo "System Configuration";
    }

    // public function userFiles() { /* Similar caution */ }
}
