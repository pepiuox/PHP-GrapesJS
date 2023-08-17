<?php
if (isset($_POST['submit'])) {
    $sql = "DELETE FROM page WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo '<div class="alert alert-primary" role="alert">';
        echo "<h4>Record deleted successfully</h4>";
        echo '</div>';
        echo "<script>
window.setTimeout(function() {
    window.location.href = 'dashboard.php?cms=pagelist.php';
}, 3000);
</script>";
    } else {
        echo '<div class="alert alert-danger" role="alert">';
        echo "Error deleting record: " . $conn->error;
        echo '</div>';
    }
}
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 pt-4">
                <div class="align-content-end">
                    <a class="btn btn-primary" href="list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                </div>
            </div>
            <div class="col-md-12 py-3">

                <h2>Are you sure you want to delete this page</h2>
                <?php
                echo '<form metho>';
                echo '<input type="submit" name="submit" class="btn btn-primary" value="Detele">' . "\n";
                echo '</form>' . "\n";
                ?>
            </div>
        </div>
    </div>
    <?php
} else {
    header('Location: dashboard.php?cms=pagelist');
}
?>
