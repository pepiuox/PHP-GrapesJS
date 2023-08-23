<?php
if (isset($_POST["submit"])) {

    // Check image using getimagesize function and get size
    // if a valid number is got then uploaded file is an image
    if (isset($_FILES["image"])) {
        // directory name to store the uploaded image files
        // this should have sufficient read/write/execute permissions
        // if not already exists, please create it in the root of the
        // project folder
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if the file already exists in the same path
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size and throw error if it is greater than
    // the predefined value, here it is 2000000
    if ($_FILES["image"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Check for uploaded file formats and allow only 
    // jpg, png, jpeg and gif
    // If you want to allow more formats, declare it here
    if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>

        <div class="container mt-3">

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Launch demo modal
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="SITE_BRAND_IMG">SITE BRAND IMG:</label>
                                    <input type="file" class="form-control" id="SITE_BRAND_IMG" name="image">
                                </div>
                                <input type="submit" name="submit" class="btn btn-primary" value="Save">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                          
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <script type="text/javascript">

            $(document).ready(function () {

                $('#loginform').submit(function (e) {

                    e.preventDefault();

                    $.ajax({

                        type: "POST",

                        url: 'login.php',

                        data: $(this).serialize(),

                        success: function (response)

                        {

                            var jsonData = JSON.parse(response);

                            // user is logged in successfully in the back-end

                            // let's redirect

                            if (jsonData.success == "1")

                            {

                                location.href = 'my_profile.php';

                            } else

                            {

                                alert('Invalid Credentials!');

                            }

                        }

                    });

                });

            });

        </script>
    </body>
</html>
