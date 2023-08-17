<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" name="viewport"
              content="width-device=width, initial-scale=1" />
        <title>Admin CRUD</title>
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/css/theme.css">
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/css/fonts.css">
        <link rel="stylesheet"
              href="<?php echo SITE_PATH; ?>/css/font-awesome.min.css">
        <!-- Custom .css -->
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/css/custom/custom.css">
        <!-- Custom font -->
        <link href="https://fonts.googleapis.com/css?family=Quicksand"
              rel="stylesheet">
        <link href="<?php echo SITE_PATH; ?>/css/bootstrap-datepicker.min.css"
              rel="stylesheet" type="text/css" />
        <link href="<?php echo SITE_PATH; ?>/css/jquery-ui.min.css" rel="stylesheet"
              type="text/css" />
        <script type="text/javascript"
        src="<?php echo SITE_PATH; ?>/js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript"
        src="<?php echo SITE_PATH; ?>/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo SITE_PATH; ?>/js/typeahead.js"></script>
        <script type="text/javascript" src="<?php echo SITE_PATH; ?>/js/vue.js"></script>
        <script type="text/javascript" src="<?php echo SITE_PATH; ?>/js/axios.js"></script>
        <script type="text/javascript"
        src="<?php echo SITE_PATH; ?>/js/popper.min.js"></script>
        <script type="text/javascript"
        src="<?php echo SITE_PATH; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo SITE_PATH; ?>/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
        <script src="<?php echo SITE_PATH; ?>/js/tinymce/tinymce.min.js"></script>
        <script>tinymce.init({selector: 'textarea'});</script>
        <script>
            $('#imagen').on('change', function () {
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(fileName);
            })
        </script>
