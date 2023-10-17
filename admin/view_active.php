<?php

$q = "SELECT username FROM uverify ORDER BY timestamp DESC, username";
$result = $conn->query($q);
/* Error occurred, return given name by default */
$num_rows = $result->num_rows;

if (!$result || ($num_rows === 0)) {
    echo "Information display error";
} else if ($num_rows > 0) {
    /* Display active users, with link to their info */
    echo '<table class="table" align="left">' . "\n";
    echo '<tr><td>' . "\n";

    for ($i = 0; $i < $num_rows; $i++) {

        mysqli_data_seek($result, $i);
        $row = mysqli_fetch_row($result);
        $uname = $row[0]; //username
        echo '<a href="userinfo.php?user=' . $uname . '">' . $uname . '</a>';
    }
    echo '</font></td></tr></table><br>' . "\n";
}
?>
