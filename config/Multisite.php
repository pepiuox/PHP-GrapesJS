<?php

class Multisite {

    private $domain;
    private $sites;

    public function __construct() {
        $this->domain = $_SERVER['HTTP_HOST'];

        $websites = require 'domains.php';
        $this->sites = $websites;
    }

    public function DomainConnection() {
        $local = 'localhost';
       
        if (str_contains($this->domain, $local)) {
            $sitenm = str_replace(":", "_", $this->domain);
        } else {
            $sitenm = str_replace(".", "_", $this->domain);
        }

        $site = $this->sites["domains"][$sitenm];

        return $site['dataserver'];
    }
}
