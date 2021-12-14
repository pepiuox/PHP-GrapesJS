<?php
//This is temporal file only for add new row
if (isset($_POST["updatequeries"])) {
$id_menu = $_POST['id_menu'];
$sql0 = "UPDATE table_queries SET query='$id_menu' WHERE tque_Id='1' ";
$conn->query($sql0);
$fluid = $_POST['fluid'];
$sql1 = "UPDATE table_queries SET query='$fluid' WHERE tque_Id='2' ";
$conn->query($sql1);
$placement = $_POST['placement'];
$sql2 = "UPDATE table_queries SET query='$placement' WHERE tque_Id='3' ";
$conn->query($sql2);
$aligment = $_POST['aligment'];
$sql3 = "UPDATE table_queries SET query='$aligment' WHERE tque_Id='4' ";
$conn->query($sql3);
$background = $_POST['background'];
$sql4 = "UPDATE table_queries SET query='$background' WHERE tque_Id='5' ";
$conn->query($sql4);
$color = $_POST['color'];
$sql5 = "UPDATE table_queries SET query='$color' WHERE tque_Id='6' ";
$conn->query($sql5);
echo "Record added successfully";
header("Location: dashboard.php?cms=querybuilder&w=editor&tbl=menu_options");
} 
?> 
