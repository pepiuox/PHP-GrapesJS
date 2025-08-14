<div class="container-fluid shadow <?php echo $background; ?>" data-bs-theme="<?php echo $color; ?>">
    <div class="row sticky-bottom">
        <div class="col-md-12 text-center p-4"><?php echo '© '. date("Y").' Copyright: '. SITE_NAME .' - Develop by PePiuoX'; ?></div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("a").find('.active').each(function () {
            $(this).parent().closest('.has-treeview').addClass("menu-open");
            $('.has-treeview').children('a').first().addClass("active");
            $('.has-treeview').find('a').each(function () {
                $(this).addClass("active");
            });
        });
        $(".nav-tabs a").click(function () {
            $(this).tab('show');
        });
    });
</script>

