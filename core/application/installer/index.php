<?php
$site= "http://{$_SERVER['HTTP_HOST']}/";
header("Location: $site");
die();
?>
