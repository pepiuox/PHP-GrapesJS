<?php

if (!isset($_SESSION)) {
    session_start();
}

/* Upload images */
if ($_FILES) {
    $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
    $imgDir = $protocol.$_SERVER['HTTP_HOST']. "/build/uploads";
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/build/uploads";
    /* Upload image */

    $resultArray = array();
    foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['file']['name'][$key]);
        $targetFilePath = $targetDir .'/'. $fileName;
        $file_size = $_FILES['file']['size'][$key];
        $file_tmp = $_FILES['file']['tmp_name'][$key];
        $file_type = $_FILES['file']['type'][$key];
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array(
            'jpg',
            'png',
            'jpeg',
            'gif'
        );
        $img_dir = $targetDir;

        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                // Insert image file name into database
                echo "The file " . $file_name . " has been uploaded successfully.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
        }
    }

    function GetImagesToFolder($targetDir,$imgDir) {
        $ImagesArray = [];
        $file_display = [
            'jpg',
            'jpeg',
            'png',
            'gif'
        ];

        if (file_exists($targetDir) == false) {
            return [
                "Directory \'', $targetDir, '\' not found!"
            ];
        } else {
            $dir_contents = scandir($targetDir);
            foreach ($dir_contents as $file) {
                $file_type = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($file_type, $file_display) == true) {
                    $ImagesArray[] = $imgDir.'/'.$file;
                }
            }
            return $ImagesArray;
        }
    }

    $ImagesA = GetImagesToFolder($targetDir,$imgDir);
    echo json_encode($ImagesA);
}
