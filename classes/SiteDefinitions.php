<?php

class SiteDefinitions {

    protected $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $result = $this->connection->prepare("SELECT config_name, config_value FROM configuration");

        while ($rowt = $result->fetch_array()) {
            $values = $rowt['config_value'];
            $names = $rowt['config_name'];
            $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
        }

        return implode(' ', $vars) . "\n";
        $this->connection->close();
    }
}
