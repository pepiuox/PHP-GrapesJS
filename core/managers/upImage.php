<?php

if (!isset($_SESSION)) {
    session_start();
}

/* Upload images */
if ($_FILES) {
    $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
    $imgDir = $protocol.$_SERVER['HTTP_HOST']. "/build/uploads";
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/build/uploads";

    $resultArray = array();
    foreach ($_FILES as $file) {
        $fileName = $file['name'];
        $tmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];

        $targetFilePath = $targetDir .'/'. $fileName;
        $targetFileSrc = $imgDir .'/'. $fileName;
        if (move_uploaded_file($tmpName, $targetFilePath)) {
            if ($file['error'] != UPLOAD_ERR_OK) {
                error_log($file['error']);
                echo JSON_encode(null);
            }

            list ($width, $height, $type, $attr) = getimagesize($targetFilePath);
            $result = array(
                'name' => $fileName,
                'type' => 'image',
                'src' => $targetFileSrc,
                'height' => $height,
                'width' => $width
            );
            // we can also add code to save images in database here.
            array_push($resultArray, $result);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $response = array(
        'data' => $resultArray
    );
    echo json_encode($response);
}
