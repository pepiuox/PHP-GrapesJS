<?php
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
$baseweb = $protocol . $_SERVER["HTTP_HOST"] . "/";
$rootweb = $_SERVER['DOCUMENT_ROOT'];
