<!-- simple block -->
<div class="row">
    <div class="col-md-12">
        <?php
        $result = $conn->query("SELECT id, title, content FROM pages WHERE id = '$id'");
        $row = $result->fetch_assoc();

        echo $row['content'];
        ?>
    </div>
</div>