<?php
// ✅ PDO: se usa la conexión canónica global ($conn) en lugar de un conn.php inexistente.
if (!isset($conn)) {
    require_once __DIR__ . '/../config/dbconnection.php';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" name="viewport" content="width-device=width,initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="styles.css" />
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>PHP CRUD</title>
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>	
        <script type="text/javascript" src="js/bootstrap.js"></script>       
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php
        $sql = "SELECT * FROM table_queries WHERE name_table='product_category'";
        $result = $conn->query($sql);
        $rowcq = $result->rowCount();
        $r = 0;

        if ($r < $rowcq) {
            $rqu = $result->fetch(PDO::FETCH_ASSOC);
            $mn = $rqu['j_value'];
            $ver = '';
            if ($rqu['type'] != '' || $rqu['query'] != '') {
                $qNameTable = \PepiuoX\Security\SqlIdentifier::assert($rqu['name_table']);
                $qJoins = \PepiuoX\Security\SqlIdentifier::fromWhitelist($rqu['joins'], ['', 'JOIN', 'INNER JOIN', 'LEFT JOIN', 'LEFT OUTER JOIN', 'RIGHT JOIN', 'RIGHT OUTER JOIN', 'STRAIGHT JOIN', 'CROSS JOIN', 'NATURAL JOIN']);
                $qJId = \PepiuoX\Security\SqlIdentifier::assert($rqu['j_id']);
                $qJValue = \PepiuoX\Security\SqlIdentifier::assert($rqu['j_value']);
                $qJTable = \PepiuoX\Security\SqlIdentifier::assert($rqu['j_table']);
                $qColName = \PepiuoX\Security\SqlIdentifier::assert($rqu['col_name']);

                $query = "SELECT * FROM " . $qNameTable . " " . $qJoins . " (SELECT " . $qJId . "," . $qJValue . " FROM " . $qJTable . " )" . $qJTable . " ON " . $qNameTable . "." . $qColName . "=" . $qJTable . "." . $qJId;

                $ver .= "SELECT * FROM " . $qNameTable . " " . $qJoins . " (SELECT " . $qJId . "," . $qJValue . " FROM " . $qJTable . " )" . $qJTable . " ON " . $qNameTable . "." . $qColName . "=" . $qJTable . "." . $qJId;
            }
            echo $ver;
            $result2 = $conn->query($query);
            while ($vw = $result2->fetch(PDO::FETCH_ASSOC)) {
                echo $vw[$mn] . " - " . $vw['category'] . "\n";
            }
        } else {
            echo 'hello';
        }
        ?>
    </body>
</html>