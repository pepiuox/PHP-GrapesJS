<?php
$rout = str_replace('\\', '/', dirname(__DIR__));
include_once $rout.'/config/dbconnection.php';

if (isset($_POST['title'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $sql = "INSERT INTO pages (title, content) VALUES (:title, :content)";
    // ✅ PDO::query() devuelve un PDOStatement (nunca TRUE) y lanza PDOException si falla.
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([':title' => protect($title), ':content' => protect($content)]) !== false) {
        $last_id = $conn->lastInsertId();
        $sqlm = "INSERT INTO menu (page_id, title_page) VALUES (:page_id, :title_page)";
        $stmtm = $conn->prepare($sqlm);
        if ($stmtm->execute([':page_id' => $last_id, ':title_page' => protect($title)]) !== false) {
            echo "Page " . $title . " : Created ";
        } else {
            echo "Failed";
        }
        $_SESSION["title"] = $title;
        $_SESSION["page"] = $last_id;
    } else {
        echo "Failed";
    }
}
?>
