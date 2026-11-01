<?php

require_once dirname(__DIR__) . '/Autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 if (isset($_FILES['files'])) {
  $errors = [];
  $path = 'uploads/';
  $extensions = ['jpg', 'jpeg', 'png', 'gif'];

  $all_files = count($_FILES['files']['tmp_name']);

  for ($i = 0; $i < $all_files; $i++) {
$file_name = $_FILES['files']['name'][$i];
$file_type = $_FILES['files']['type'][$i];
$file_size = $_FILES['files']['size'][$i];

$file = [
 'name' => $file_name,
 'tmp_name' => $_FILES['files']['tmp_name'][$i],
 'size' => $file_size,
 'error' => $_FILES['files']['error'][$i],
 'type' => $file_type,
];

$upload_error = null;
$stored = \PepiuoX\Security\Upload::storeImage($file, $path, \PepiuoX\Security\Upload::DEFAULT_MAX_BYTES, $upload_error);

if ($stored === null) {
 if (!in_array(\PepiuoX\Security\Upload::extension($file_name), $extensions, true)) {
  $errors[] = 'Extension not allowed: ' . htmlspecialchars($file_name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' ' . htmlspecialchars($file_type, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
 } elseif ($file_size > \PepiuoX\Security\Upload::DEFAULT_MAX_BYTES) {
  $errors[] = 'File size exceeds limit: ' . htmlspecialchars($file_name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . ' ' . htmlspecialchars($file_type, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
 } else {
  $errors[] = $upload_error;
 }
}
  }

  if ($errors)
print_r($errors);
 }
}
