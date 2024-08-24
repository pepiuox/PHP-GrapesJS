<!-- content block -->
<div class="content">    
    <?php
    if ($bid) {
        $result = $conn->query("SELECT id, title, content FROM pages WHERE id = '$bid'");
    }
    $row = $result->fetch_assoc();
    echo $row['content'];
    ?>
</div>
