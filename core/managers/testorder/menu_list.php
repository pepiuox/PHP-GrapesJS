
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
    // ✅ PDO: conexión canónica global (antes $database, inexistente).
    if (!isset($conn)) {
        require_once __DIR__ . '/../../config/dbconnection.php';
    }
    $result = $conn->query("SELECT * FROM `menu` ORDER BY `sort` ASC");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<li id="menu_' . $row['id'] . '">' . $row['title'] . "</li>\n";
    }
    ?>
</ul>
