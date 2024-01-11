
<?php

$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');
echo $now, "\n";
$time = strtotime($now);
$tim = $time - (60 * 60); //one hour
$beforeOneHour = date("Y-m-d H:i:s", $tim);

echo $beforeOneHour, "\n";
?>
