<?php

/* Upload images */
if ($_FILES) {
    $targetDir = "../uploads";
    $resultArray = array();
    foreach ($_FILES as $file) {
        $fileName = $file['name'];
        $tmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];

        $targetFilePath = $targetDir . '/' . $fileName;

        if (move_uploaded_file($tmpName, $targetFilePath)) {
            if ($file['error'] != UPLOAD_ERR_OK) {
                error_log($file['error']);
                echo JSON_encode(null);
            }

            list ($width, $height, $type, $attr) = getimagesize($targetFilePath);
            $result = array(
                'name' => $fileName,
                'type' => 'image',
                'src' => $targetFilePath,
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
