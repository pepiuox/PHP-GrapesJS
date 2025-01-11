<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
<?php
require_once 'metalink.php';

if (!empty($description)) {
?>
    <meta name="description" content="<?php echo $description; ?>" />
<?php } else { ?>
    <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>" />
    <?php
}
if (!empty($keyword)) {
    ?>
    <meta name="keywords" content="<?php echo $keyword; ?>" />
<?php } else { ?>
    <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />
    <?php
}
if (!empty($classification)) {
    ?>
    <meta name="classification" content="<?php echo $classification; ?>" />
<?php } else { ?>
    <meta name="classification" content="<?php echo SITE_CLASSIFICATION; ?>" />
<?php } ?>
<title><?php echo $title; ?></title>
<link href="<?php echo SITE_PATH; ?>assets/css/theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_PATH; ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/js/popper.min.js" type="text/javascript"></script> 
<script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
