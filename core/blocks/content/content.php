<!-- content block -->

    <div class="row">    

        <?php
        if ($bid) {
            $result = $conn->prepare("SELECT id, title, content FROM pages WHERE id = :bid");
            $result->execute([':bid' => $bid]);
        }
        $row = $result->fetch(PDO::FETCH_ASSOC);

        echo $row['content'];
        ?>
    </div>
