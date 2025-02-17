<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
        <title><?php echo SITE_NAME; ?> | <?php echo $fname; ?></title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/fontawesome/css/all.min.css">        
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/adminlte/css/adminlte.min.css">
        <style>
            .color-palette {
                height: 35px;
                line-height: 35px;
                text-align: right;
                padding-right: .75rem;
            }

            .color-palette.disabled {
                text-align: center;
                padding-right: 0;
                display: block;
            }

            .color-palette-set {
                margin-bottom: 15px;
            }

            .color-palette span {
                display: none;
                font-size: 12px;
            }

            .color-palette:hover span {
                display: block;
            }

            .color-palette.disabled span {
                display: block;
                text-align: left;
                padding-left: .75rem;
            }

            .color-palette-box h4 {
                position: absolute;
                left: 1.25rem;
                margin-top: .75rem;
                color: rgba(255, 255, 255, 0.8);
                font-size: 12px;
                display: block;
                z-index: 7;
            }
        </style>

        <!-- jQuery -->
        <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js"></script>
        <script>
            jQuery.htmlPrefilter = function (html) {
                return html;
            };
        </script>
        <!-- Bootstrap 5 -->
        <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo SITE_PATH; ?>assets/plugins/adminlte/js/adminlte.min.js"></script>
