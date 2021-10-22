<?php

$remote_file_url = "http://perucompras.mantinni.com/controlador.zip";

/* New file name and path for this file */
$local_file = 'files.zip';

/* Copy the file from source url to server */
$copy = copy($remote_file_url, $local_file);

/* Add notice for success/failure */
if (!$copy) {
    echo "Doh! failed to copy" . $file . "\n";
} else {
    echo "WOOT! success to copy". $file. " \n";
}
?>
