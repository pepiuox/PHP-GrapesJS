<script type="text/javascript">
    function saveOrder() {
        var articleorder = "";
        $("#sortable li").each(function (i) {
            if (articleorder == '')
                articleorder = $(this).attr('data-article-id');
            else
                articleorder += "," + $(this).attr('data-article-id');
        });
        //articleorder now contains a comma separated list of the ID's of the articles in the correct order.
        $.post('/saveorder.php', {order: articleorder})
                .success(function (data) {
                    alert('saved');
                })
                .error(function (data) {
                    alert('Error: ' + data);
                });
    }
</script>
<ul id="sortable">
<?php
//my way to get all the articles, but you should of course use your own method.
$articles = Page::Articles();
foreach ($articles as $article) {
?>
            <li data-article-id='<?php $article->Id(); ?>'><?php $article->Title(); ?></li>
    <?php
}
    ?>   
</ul>
<input type='button' value='Save order' onclick='saveOrder();'/>