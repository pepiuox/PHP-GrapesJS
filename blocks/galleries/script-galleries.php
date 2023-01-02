<script src="<?php echo SITE_PATH; ?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/js/image-scale.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/js/galleries.js" type="text/javascript"></script>
<link href="<?php echo SITE_PATH; ?>assets/css/galleries.css" rel="stylesheet" type="text/css"/>
<link rel="Stylesheet" type="text/css" href="<?php echo SITE_PATH; ?>assets/css/smoothDivScroll.css" />
<script src="<?php echo SITE_PATH; ?>assets/js/jquery.mousewheel.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/js/jquery.kinetic.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/js/jquery.smoothdivscroll-1.3-min.js" type="text/javascript"></script>	
<script src="<?php echo SITE_PATH; ?>assets/js/jquery.colorbox-min.js" type="text/javascript"></script>		
<script type="text/javascript">
    // Initialize the plugin with no custom options
    $(document).ready(function () {
        // I just set some of the options
        $("#makeMeScrollable").smoothDivScroll({

        });
    });   
</script>

<style type="text/css">

    #makeMeScrollable
    {
        width:100%;
        height: 80px;
        position: relative;
        top: 0px;
        margin: 10px 0px 20px 0px;
    }
    .scrollWrapper{
        max-width: 100%;
        position: absolute;       
    }        
    #makeMeScrollable div.scrollableArea *
    {
        position: relative;
        display: block;        
        text-align: center;
        margin: 0;
        padding: 0;               
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        user-select: none;
    }        
    #TemporaryPushBox {
        border: solid 1px #e5ece5;
        padding: 20px;

        background: rgb(237,237,237); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(237,237,237,1) 0%, rgba(246,246,246,1) 53%, rgba(255,255,255,1) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(237,237,237,1)), color-stop(53%,rgba(246,246,246,1)), color-stop(100%,rgba(255,255,255,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  rgba(237,237,237,1) 0%,rgba(246,246,246,1) 53%,rgba(255,255,255,1) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  rgba(237,237,237,1) 0%,rgba(246,246,246,1) 53%,rgba(255,255,255,1) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  rgba(237,237,237,1) 0%,rgba(246,246,246,1) 53%,rgba(255,255,255,1) 100%); /* IE10+ */
        background: linear-gradient(to bottom,  rgba(237,237,237,1) 0%,rgba(246,246,246,1) 53%,rgba(255,255,255,1) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
    }

</style>