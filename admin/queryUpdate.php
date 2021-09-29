<?php

require '../config/dbconnection.php';
$tble = 'volunteer';
$ncol = 'id';
$result = $conn->query("SELECT * FROM $tble");
$fields = $result->field_count;
for ($i = 0; $i < $fields; $i++) {
    $finfo = $result->fetch_field_direct($i);
    if ($finfo->name == $ncol) {
        continue;
    } else {
        $nq = $conn->query("SELECT COLUMN_TYPE as type FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $finfo->table . "' AND COLUMN_NAME = '" . $finfo->name . "'");
        $row = $nq->fetch_assoc();
        $nt = strcspn($row['type'], '(');
        $ns = substr($row['type'], 0, $nt);
        $intdata = ['int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'bit', 'float', 'double', 'decimal', 'time', 'year', 'date', 'datetime', 'timestamp'];
        $strdata = ['varchar', 'char', 'text', 'tinytext', 'mediumtext', 'longtext', 'json', 'enum', 'set'];
        $doudata = ['binary', 'varbinary', 'tinyblob', 'blob', 'mediumblob', 'longblob'];
        $blodata = ['point', 'linestring', 'polygon', 'geometry', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection'];

        if (in_array($ns, $intdata)) {
            $bp[] = 'i';
        } elseif (in_array($ns, $strdata)) {
            $bp[] = 's';
        } elseif (in_array($ns, $doudata)) {
            $bp[] = 'd';
        } elseif (in_array($ns, $blodata)) {
            $bp[] = 'b';
        }
        $vd = implode("", $bp);
    }
}
if ($fields > $r) {
    while ($info = mysqli_fetch_field($result)) {
        if ($info->name != $ncol) {
            $postnames[] = '$' . $info->name . ' = $_POST["' . $info->name . '"]; ' . "\r\n";
            $varnames[] = $info->name . " = ?";
            $bname[] = "$" . $finfo->name;
        }
    }
}
$scpt = implode("", $postnames);
$ecols = implode(", ", $varnames);
$bnames = implode(", ", $bname);

$fichero = 'qupdate.php';
$myfile = fopen("$fichero", "w") or die("Unable to open file!");
$content = '<?php' . "\n";
$content .= '//This is temporal file only for add new row' . "\n";
$content .= "if (isset(\$_POST['editrow'])) { \r\n";
$content .= $scpt . "\r\n";
$content .= '$query="UPDATE `$tble` SET ' . $ecols . ' WHERE ' . $ncol . '=? ";' . "\r\n";
$content .= "\$insert = \$conn->prepare(\$sql);
\$insert->bind_param('" . $vd . "i'," . $bnames . ",\$id );
\$insert->execute();
\$insert->close();" . "\n";
$content .= "}" . "\n";
$content .= "?> \n";

fwrite($myfile, $content);
fclose($myfile);
