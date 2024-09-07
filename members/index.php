<?php

if (!isset($_SESSION)) {
	session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
	require_once '../config/dbconnection.php';
	require_once 'Autoload.php';

	$login = new UserClass();

	if ($login->isLoggedIn() === true) {

		header('Location: profile.php');
		exit();
	} else {
		header('Location: ' . SITE_PATH . 'signin/login.php');
		exit();
	}
} else {
	header('Location: ../installer/install.php');
	exit();
}
?>
