<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo SITE_NAME; ?> | <?php echo $fname; ?></title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/fontawesome-free/css/all.min.css">        
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/adminlte/css/adminlte.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <?php if ($fname === 'index') { ?>
            <!-- Ionicons -->
            <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">        
            <!-- Tempusdominus Bootstrap 4 -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
            <!-- iCheck -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
            <!-- JQVMap -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/jqvmap/jqvmap.min.css">        
            <!-- Daterange picker -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/daterangepicker/daterangepicker.css">
            <!-- summernote -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/summernote/summernote-bs4.min.css">
        <?php } if ($fname === 'gallery') { ?>
            <!-- Ekko Lightbox -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/ekko-lightbox/ekko-lightbox.css">
        <?php } if ($fname === 'calendar') { ?>
            <!-- fullCalendar -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/fullcalendar/main.min.css">
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/fullcalendar-daygrid/main.min.css">
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/fullcalendar-timegrid/main.min.css">
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/fullcalendar-bootstrap/main.min.css">
        <?php } if ($fname === 'editor' || $fname === 'compoese') { ?>
            <!-- summernote -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/summernote/summernote-bs4.min.css">
        <?php } if ($fname === 'advanced') { ?>
            <!-- daterange picker -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/daterangepicker/daterangepicker.css">
            <!-- iCheck for checkboxes and radio inputs -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
            <!-- Bootstrap Color Picker -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
            <!-- Tempusdominus Bootstrap 4 -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
            <!-- Select2 -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/select2/css/select2.min.css">
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
            <!-- Bootstrap4 Duallistbox -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
        <?php } if ($fname === 'navbar') { ?>
            <!-- SweetAlert2 -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/sweetalert2/sweetalert2.min.css">
            <!-- Toastr -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/toastr/toastr.min.css">
        <?php } if ($fname === 'modals') { ?>
            <!-- SweetAlert2 -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
            <!-- Toastr -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/toastr/toastr.min.css">
        <?php } if ($fname === 'general') { ?>        
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
        <?php } if ($fname === 'mailbox' || $fname === 'login' || $fname === 'register' || $fname === 'forgot-password' || $fname === 'recover-password') { ?>
            <!-- icheck bootstrap -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <?php } if ($fname === 'data') { ?>
            <!-- DataTables -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <?php } if ($fname === 'jsgrid') { ?>
            <!-- jsGrid -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/jsgrid/jsgrid.min.css">
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/jsgrid/jsgrid-theme.min.css">
        <?php } if ($fname === 'language-menu') { ?>
            <!-- flag-icon-css -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/flag-icon-css/css/flag-icon.min.css">       
        <?php } if ($fname === 'pace') { ?>
            <!-- pace-progress -->
            <link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/pace-progress/themes/black/pace-theme-flat-top.css">
        <?php } ?>
