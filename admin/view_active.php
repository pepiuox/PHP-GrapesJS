<?php

$q = "SELECT username FROM uverify ORDER BY timestamp DESC, username";
$result = $conn->query($q);
/* Error occurred, return given name by default */
$num_rows = $result->num_rows;

if (!$result || ($num_rows === 0)) {
	o "Information display error";
} else if ($num_rows > 0) {
	Display active users, with link to their info */
	o '<table class="table" align="left">' . "\n";
	o '<tr><td>' . "\n";

	 ($i = 0; $i < $num_rows; $i++) {

		_data_seek($result, $i);
		 mysqli_fetch_row($result);
		 = $row[0]; //username
		<a href="userinfo.php?user=' . $uname . '">' . $uname . '</a>';

	o '</font></td></tr></table><br>' . "\n";
}
?>
