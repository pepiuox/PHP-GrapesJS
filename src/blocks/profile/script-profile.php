<style>
    #myProfile{
        display: block;
        width: 100%;
        margin: 0 auto;
    }
    form.br{
        color: #B5B6C3;
        padding-left: 20px;
        border-left: 1px solid #B5B6C3;
    }
    form.br label{
        color: #B5B6C3;
    }
    .myPhoto{
        width: 200px;
        height: 260px;
    }
    .infocontact{ 
        position: relative;
        float: right;        
    }
    .infocontact p{  
        color: #B5B6C3;
        text-align: right;
        margin-right: 10px;
    }
    .infcont{
        width: 100%;
        text-align: right;
        padding-right: 40px;
    }
    .error {color: #FF0000;}
    .scrollpanel {
        width: 100%px;
        height: 380px;    
        border-left: 1px solid #B5B6C3;        

        .sp-scrollbar {
            width: 10px;
            margin: 4px;
            background-color: #ccc;
            cursor: pointer;

            .sp-thumb {
                background-color: #aaa;
            }
        }
        .sp-scrollbar.active
        .sp-thumb {
            background-color: #999;
        }
    }
    
</style>
<script src="<?php echo SITE_PATH; ?>assets/js/image-scale.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_PATH; ?>assets/js/jquery.jscrollpane.min.js" type="text/javascript"></script>
<script>
    $(function () {
        $("img.scale").imageScale({
            rescaleOnResize: true
        });
        $('.scrollpanel').scrollpanel();
    });
</script>