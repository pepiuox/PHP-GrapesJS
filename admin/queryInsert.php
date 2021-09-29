<?php

require '../config/dbconnection.php';
$tble = 'volunteer';
$ncol = 'id';

$result = $conn->query("SELECT * FROM $tble");
while ($finfo = $result->fetch_field()) {
    if ($finfo->name == $ncol) {
        continue;
    }
    $vname[] = $finfo->name;
    $bname[] = "$" . $finfo->name;
    $pname[] = "?";
    $ptadd[] = "$" . $finfo->name . " = \$_POST['" . $finfo->name . "'];" . "\n";
}
$fields = $result->field_count;
$bp = array();

for ($i = 0; $i < $fields; $i++) {
    $finfo = $result->fetch_field_direct($i);
    if (!$finfo->name == $ncol) {
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

$vnames = implode(", ", $vname);
$bnames = implode(", ", $bname);
$pnames = implode(", ", $pname);
$ptadds = implode(" ", $ptadd);

$rvfile = 'qinsert.php';
$mfile = fopen("$rvfile", "w") or die("Unable to open file!");
$content = '<?php' . "\n";
$content .= '//This is temporal file only for add new row' . "\n";
$content .= "if(isset(\$_POST['addrow'])){" . "\n";
$content .= $ptadds . "\n";
$content .= '$sql = "INSERT INTO ' . $tble . ' (' . $vnames . ')' . "\n";
$content .= 'VALUES (' . $pnames . ')";' . "\n";
$content .= "\$insert = \$conn->prepare(\$sql);
\$insert->bind_param('" . $vd . "'," . $bnames . " );
\$insert->execute();
\$insert->close();" . "\n";
$content .= "}" . "\n";
$content .= "?> \n";

fwrite($mfile, $content);
fclose($mfile);

