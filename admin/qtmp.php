<?php
//This is temporal file only for add new row
if (isset($_POST["updatequeries"])) {
$sort = $_POST['sort'];
$sql0 = "UPDATE table_queries SET query='$sort' WHERE tque_Id='1' ";
$conn->query($sql0);
$page_id = $_POST['page_id'];
$sql1 = "UPDATE table_queries SET query='$page_id' WHERE tque_Id='2' ";
$conn->query($sql1);
$title_page = $_POST['title_page'];
$sql2 = "UPDATE table_queries SET query='$title_page' WHERE tque_Id='3' ";
$conn->query($sql2);
$link_page = $_POST['link_page'];
$sql3 = "UPDATE table_queries SET query='$link_page' WHERE tque_Id='4' ";
$conn->query($sql3);
$parent_id = $_POST['parent_id'];
$sql4 = "UPDATE table_queries SET query='$parent_id' WHERE tque_Id='5' ";
$conn->query($sql4);
echo "Record added successfully";
header("Location: dashboard.php?cms=querybuilder&w=editor&tbl=menu");
} 
?> 
