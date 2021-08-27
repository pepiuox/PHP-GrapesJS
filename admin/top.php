<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" name="viewport"
              content="width-device=width, initial-scale=1" />
        <title>Admin CRUD</title>
        <link rel="stylesheet" href="<?php echo $base; ?>/css/theme.css">
        <link rel="stylesheet" href="<?php echo $base; ?>/css/fonts.css">
        <link rel="stylesheet"
              href="<?php echo $base; ?>/css/font-awesome.min.css">
        <!-- Custom .css -->
        <link rel="stylesheet" href="<?php echo $base; ?>/css/custom/custom.css">
        <!-- Custom font -->
        <link href="https://fonts.googleapis.com/css?family=Quicksand"
              rel="stylesheet">
        <link href="<?php echo $base; ?>/css/bootstrap-datepicker.min.css"
              rel="stylesheet" type="text/css" />
        <link href="<?php echo $base; ?>/css/jquery-ui.min.css" rel="stylesheet"
              type="text/css" />
        <script type="text/javascript"
        src="<?php echo $base; ?>/js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript"
        src="<?php echo $base; ?>/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo $base; ?>/js/typeahead.js"></script>
        <script type="text/javascript" src="<?php echo $base; ?>/js/vue.js"></script>
        <script type="text/javascript" src="<?php echo $base; ?>/js/axios.js"></script>
        <script type="text/javascript"
        src="<?php echo $base; ?>/js/popper.min.js"></script>
        <script type="text/javascript"
        src="<?php echo $base; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo $base; ?>/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
        <script src="<?php echo $base; ?>/js/tinymce/tinymce.min.js"></script>
        <script>tinymce.init({selector: 'textarea'});</script>
        <script>
            $('#imagen').on('change', function () {
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(fileName);
            })
        </script>
        