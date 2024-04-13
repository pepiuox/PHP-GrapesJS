<?php

class Autoload {
   
    static public function loader($classes) {
        $path_app = dirname(__DIR__, 3);
        $source = str_replace('\\', '/', $path_app);
        $filename = $source."/core/controller/" . str_replace("\\", '/', $classes) . ".php";
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
