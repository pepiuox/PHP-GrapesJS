<?php

class columnSettings {

    private $connection;

    public function __contruct($db) {
        $this->connection = $db;
    }

    public function setList($value) {
        if ($value === 1) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
    public function setView($value) {
        if ($value === 1) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
    public function setAdd($value) {
        if ($value === 1) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
    public function setUpdate($value) {
        if ($value === 1) {
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
?>
