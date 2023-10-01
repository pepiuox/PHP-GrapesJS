
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.9/beautify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.9/beautify-css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.9/beautify-html.min.js"></script>
