<!-- simple block -->
<div class="row">
    <div class="col-md-12">
        <?php
        $result = $conn->prepare("SELECT id, title, content FROM pages WHERE id = :id");
        $result->execute([':id' => $id]);
        $row = $result->fetch(PDO::FETCH_ASSOC);

        echo $row['content'];
        ?>
    </div>
</div>