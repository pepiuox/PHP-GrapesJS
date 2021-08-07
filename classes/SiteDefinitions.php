<?php

class SiteDefinitions {

    public function __construct() {
        global $conn;

        $result = $conn->query("SELECT config_name, config_value FROM configuration");

        while ($rowt = $result->fetch_array()) {
            $values = $rowt['config_value'];
            $names = $rowt['config_name'];
            $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
        }

        return implode(' ', $vars) . "\n";
        /*
          $definefiles = $base.'classes/define.php';
          if (!file_exists($definefiles)) {
          $ndef = '<?php'. "\n";
          $ndef .= implode(' ', $vars). "\n";
          $ndef .= '?>'. "\n";
          file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
          }
          include $base.'classes/define.php';
         * 
         */
    }

}
