<?php

$q = "SELECT username FROM uverify ORDER BY timestamp DESC, username";
$result = $conn->query($q);
/* ✅ PDO: fetchAll reemplaza mysqli_data_seek() + mysqli_fetch_row() */
$rows = $result->fetchAll(PDO::FETCH_NUM);
$num_rows = count($rows);

if (!$result || ($num_rows === 0)) {
    echo "Information display error";
} else if ($num_rows > 0) {
    /* Display active users, with link to their info */
    echo '<table class="table" align="left">' . "\n";
    echo '<tr><td>' . "\n";

    foreach ($rows as $row) {
        $uname = $row[0]; //username
        echo '<a href="userinfo.php?user=' . $uname . '">' . $uname . '</a>';
    }
    echo '</font></td></tr></table><br>' . "\n";
}
?>
