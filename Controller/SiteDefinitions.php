<?php
// This class is in development
class SiteDefinitions {

    protected $connection;
    protected $table;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $this->table = 'FROM site_configuration';
        $this->DefineConf($this->table);
    }

    public function getAllData($tble) {
        return $this->connection->query("SELECT * FROM $tble");
    }

    public function selectData($query) {
        return $this->connection->query($query);
    }

    public function getID($tble) {
        if ($result = $this->getAllData($tble)) {
            /* Get field information for 2nd column */
            $result->field_seek(0);
            $finfo = $result->fetch_field();
            return $finfo->name;
        }
    }

    public function getColumnNames($tble) {
        $sql = 'DESCRIBE ' . $tble;
        $result = $this->selectData($sql);
        $rows = array();
        while ($row = $result->fetch_fields()) {
            $rows[] = $row['Field'];
        }
        return $rows;
    }

    public function DefineConf($tble) {
        $result = $this->getAllData($tble);
        $this->getColumnNames($tble);
        while ($rowt = $result->fetch_array()) {
            $values = $rowt['config_value'];
            $names = $rowt['config_name'];
            $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
        }

        return implode(' ', $vars) . "\n";
    }
}
