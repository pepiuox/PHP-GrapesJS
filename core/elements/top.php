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
<?php } 
if(!empty($title)){
    ?>
        <title><?php echo $title . ' - ' . SITE_NAME; ?></title>
        <?php
}else{
    ?>
        <title><?php echo $fname . ' - ' . SITE_NAME; ?></title>
        <?php
}
?>
        


<?php
$actth = 'Yes';
$bsdft = 'Yes';
$theme = $conn->prepare(
        "SELECT * FROM themes WHERE base_default = ? AND active_theme = ? "
);
$theme->bind_param("ss", $bsdft, $actth);
$theme->execute();
$tm = $theme->get_result();
$theme->close();
$nt = $tm->num_rows;

if ($nt > 0) {
    $thm = $tm->fetch_assoc();
?>
            <link href="<?php echo SITE_PATH; ?>themes/<?php echo $thm['theme_bootstrap']; ?>/bootstrap.css" rel="stylesheet" type="text/css"/>
    <?php
} else {
    ?>
            <link href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <?php
}
    ?>

<link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/adminlte/css/adminlte.min.css">       
<!-- Font Awesome -->
<link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/css/menu.css" />
<!-- botstrap, jquery, pooper -->
<script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/js/menu.js" type="text/javascript"></script>
<style>
    #wrapper, .container-fluid{
margin:0;
padding:0;
    }
</style>

