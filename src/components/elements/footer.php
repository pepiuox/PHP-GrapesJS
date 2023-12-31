
<script>
    $(document).ready(function () {
        $("a").find('.active').each(function () {
            $(this).parent().closest('.has-treeview').addClass("menu-open");
            $('.has-treeview').children('a').first().addClass("active");
            $('.has-treeview').find('a').each(function () {
                $(this).addClass("active");
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        $(".nav-tabs a").click(function () {
            $(this).tab('show');
        });
    });
</script>

