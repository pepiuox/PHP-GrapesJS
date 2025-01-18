<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class QueryCreator {

    protected $conn;
    private $DBname = 'ecommerce';
    public $table = 'proveedores';

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function SelectData() {
        $content = '';
        $colmns = $this->conn->query("SELECT COLUMN_NAME AS name, DATA_TYPE AS type
            FROM information_schema.columns WHERE
            table_schema = '$this->DBname'
            AND table_name = '$this->table'")->fetchAll(PDO::FETCH_OBJ);
        foreach ($colmns as $names) {
            $name[] = $names->name;
        }
        echo implode(",", $name) . "\n";
        foreach ($colmns as $colmn) {
            $qnames[] = "$" . $colmn->name . " = \$_POST['" . $colmn->name . "'];" . "\n";
        }
        $content .= implode("", $qnames) . "\n";
        foreach ($colmns as $qcols) {
            $cnames[] = $qcols->name . " = :" . $qcols->name;
        }
        $colsn = implode(", ", $cnames);

        $qry = '$query = "INSERT INTO "' . $this->table . '" SET " ' . $colsn . '"';
        $pre = '$result = $this->conn->prepare($query);' . "\n";
        $content .= $qry . "\n";
        $content .= $pre . "\n";

        foreach ($colmns as $hcols) {
            $hnames[] = "$" . $hcols->name . " = htmlspecialchars(strip_tags($" . $hcols->name . "));" . "\n";
        }
        $content .= implode("", $hnames) . "\n";

        foreach ($colmns as $bcols) {

            $bnames[] = "\$stmt->bindParam(':" . $bcols->name . "',$" . $bcols->name . ");" . "\n";
        }
        $content .= implode("", $bnames);
        $content .= '
        if ($stmt->execute()) {
            return true;
        }else{
        return false;
        }' . "\n";

        return $content;
    }
}

