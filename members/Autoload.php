<?php

class Autoload {

    static public function loader($classes) {

        $filename = "../classes/" . str_replace("\\", '/', $classes) . ".php";
        if (file_exists($filename)) {
            include($filename);
            if (class_exists($classes)) {
                return TRUE;
            }
        } else {
            throw new Exception("No existe $classes.");
        }
        return FALSE;
    }

}

spl_Autoload_register('Autoload::loader');
?>
