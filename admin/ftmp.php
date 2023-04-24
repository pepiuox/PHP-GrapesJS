<?php
if(isset($_POST['addrow'])){
$language = $_POST['language'];
 $position = $_POST['position'];
 $title = $_POST['title'];
 $link = $_POST['link'];
 $url = $_POST['url'];
 $keyword = $_POST['keyword'];
 $classification = $_POST['classification'];
 $description = $_POST['description'];
 $image = $_POST['image'];
 $type = $_POST['type'];
 $menu = $_POST['menu'];
 $hidden_page = $_POST['hidden_page'];
 $path_file = $_POST['path_file'];
 $script_name = $_POST['script_name'];
 $template = $_POST['template'];
 $base_template = $_POST['base_template'];
 $content = $_POST['content'];
 $style = $_POST['style'];
 $startpage = $_POST['startpage'];
 $level = $_POST['level'];
 $parent = $_POST['parent'];
 $sort = $_POST['sort'];
 $active = $_POST['active'];
 $update = $_POST['update'];

$sql = "INSERT INTO page (language, position, title, link, url, keyword, classification, description, image, type, menu, hidden_page, path_file, script_name, template, base_template, content, style, startpage, level, parent, sort, active, update)
VALUES ('$language', '$position', '$title', '$link', '$url', '$keyword', '$classification', '$description', '$image', '$type', '$menu', '$hidden_page', '$path_file', '$script_name', '$template', '$base_template', '$content', '$style', '$startpage', '$level', '$parent', '$sort', '$active', '$update')";
if ($this->connection->query($sql) === TRUE) {
    $_SESSION['success'] = 'The data was added correctly';
header('Location: dashboard.php?cms=crud&w=list&tbl=page');
} else {
    $_SESSION['error'] = 'Error: ' . $this->connection->error;
}

$this->connection->close();
}?> 
