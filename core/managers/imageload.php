<?php
require_once dirname(__DIR__) . '/Autoload.php';

if (isset($_POST["submit"])) {

    $uploadOk = 0;
    $stored = null;
    $targetFile = '';
    $upload_error = null;

    if (isset($_FILES["image"])) {
        // directory name to store the uploaded image files
        // this should have sufficient read/write/execute permissions
        // if not already exists, please create it in the root of the
        // project folder
        $targetDir = $_SERVER['DOCUMENT_ROOT']."/build/uploads/";
        // Validate and save with a server-generated name: the client filename is
        // only used to read the extension, never to build the destination path.
        $stored = \PepiuoX\Security\Upload::storeImage($_FILES["image"], $targetDir, 2000000, $upload_error);
        $uploadOk = $stored !== null ? 1 : 0;
        $targetFile = $stored !== null ? $targetDir . $stored : '';
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        if ($upload_error !== null) {
            echo " " . htmlspecialchars($upload_error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
    } else {
        echo "The file " . htmlspecialchars($stored, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . " has been uploaded.";
    }
}
?>

        <div class="container">
            <h1>Upload an Image</h1>
            <form action="" method="post" enctype="multipart/form-data">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
                <div class="row">
                    <input type="file" name="image" required>
                    <input type="submit" name="submit" value="Upload">
                </div>
            </form>

            <h1>Display uploaded Image:</h1>
            <?php if (isset($_FILES["image"]) && $uploadOk == 1) : ?>
                <img src="<?php echo $targetFile; ?>" alt="Uploaded Image">
            <?php endif; ?>
        </div>
   