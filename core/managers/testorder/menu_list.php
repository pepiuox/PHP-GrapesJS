
<script>
    $(document).ready(
            function () {
                $("#sortme").sortable({
                    update: function () {
                        serial = $('#sortme').sortable('serialize');
                        $.ajax({
                            url: "sort_menu.php",
                            type: "post",
                            data: serial,
                            error: function () {
                                alert("theres an error with AJAX");
                            }
                        });
                    }
                });
            }
    );
</script>

<h1>Menu List</h1>

<ul id="sortme">
    <?php
    $result = $database->query("SELECT * FROM `menu` ORDER BY `sort` ASC") or die($database->error);
    while ($row = $result->fetch_array()) {
        echo '<li id="menu_' . $row['id'] . '">' . $row['title'] . "</li>\n";
    }
    ?>
</ul>
