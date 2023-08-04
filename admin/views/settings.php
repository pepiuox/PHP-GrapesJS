<?php
$result = $conn->query("SELECT * FROM `site_configuration` WHERE `ID_Site` = '1'") or trigger_error($conn->error);
$confs = $result->fetch_assoc();

/*
 * Upload images before update table
 */

function UploadImage($image) {
    global $conn;
    // Check image using getimagesize function and get size
    // if a valid number is got then uploaded file is an image
    if (!empty($_FILES[$image]["name"])) {
        $nimage = $_FILES[$image]["name"];
        if (isset($_FILES[$image])) {

            // directory name to store the uploaded image files
            // this should have sufficient read/write/execute permissions
            // if not already exists, please create it in the root of the
            // project folder
            $targetDir = "../uploads/";
            $targetFile = $targetDir . basename($_FILES[$image]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES[$image]["tmp_name"]);
            if ($check !== false) {
                // $_SESSION['SuccessMessage'] = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                // $_SESSION['ErrorMessage'] = "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if the file already exists in the same path
        if (file_exists($targetFile)) {
            // $_SESSION['ErrorMessage'] = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size and throw error if it is greater than
        // the predefined value, here it is 2000000
        if ($_FILES[$image]["size"] > 2000000) {
            // $_SESSION['ErrorMessage'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Check for uploaded file formats and allow only 
        // jpg, png, jpeg and gif
        // If you want to allow more formats, declare it here
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            // $_SESSION['ErrorMessage'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['ErrorMessage'] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES[$image]["tmp_name"], $targetFile)) {
                $id = 1;
                $upim = $conn->prepare("UPDATE site_configuration SET $image = ? WHERE `ID_Site` = ?");
                $upim->bind_param("si", $nimage, $id);
                $upim->execute();
                $upim->close();

                $_SESSION['SuccessMessage'] = "The file " . htmlspecialchars(basename($_FILES[$image]["name"])) . " has been uploaded.";
            } else {
                $_SESSION['ErrorMessage'] = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

extract($_POST);

if (isset($_POST['Update'])) {
    if ($_FILES) {
        extract($_FILES);
        foreach ($_FILES as $k => $v) {
            $v = $k;
            UploadImage($v);
        }
    }

    /* update table */
    foreach ($_POST as $k => $v) {
        if ($_POST['Update'] === $v) {
            continue;
        }
        $vals[] = "`" . $k . "` = '" . $v . "'";
    }
    $vupdates = implode(", ", $vals);
    $update = ("UPDATE site_configuration SET $vupdates WHERE `ID_Site` = '1'");

    if ($conn->query($update) === TRUE) {
        $definefiles = '../config/define.php';

        $_SESSION['SuccessMessage'] = "Web Site Configuration : Updated.";
        $sql = "SELECT * FROM site_configuration WHERE `ID_Site` = '1'";

        if ($result = $conn->query($sql)) {
            $fname = $result->fetch_fields();
            $fdata = $result->fetch_assoc();

            foreach ($fname as $val) {
                if ($val->name === 'ID_Site') {
                    continue;
                } elseif ($val->name === 'CREATE') {
                    continue;
                } elseif ($val->name === 'UPDATED') {
                    continue;
                }
                $fldname[] = "define('" . $val->name . "','" . $fdata[$val->name] . "');" . "\n";
            }

            if (!file_exists($definefiles)) {
                $ndef = '<?php' . "\n";
                $ndef .= implode(" ", $fldname);
                $ndef .= '?>' . "\n";
                file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
            } else {
                unlink($definefiles);
                $ndef = '<?php' . "\n";
                $ndef .= implode("\n ", $fldname);
                $ndef .= '?>' . "\n";
                file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
            }
        }
    } else {
        $_SESSION['ErrorMessage'] = "Updated settings : Error.";
        header("Location: dashboard.php?cms=siteconf");
        exit();
    }
}
?>
<div class="container">            
    <div class="row">
        <div class="card">
            <div class="card-header p-2">
                <h4>Web Site Configuration</h4>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#website" data-toggle="tab">Web Site</a></li>
                    <li class="nav-item"><a class="nav-link" href="#controls" data-toggle="tab">Controls</a></li>
                    <li class="nav-item"><a class="nav-link" href="#builder" data-toggle="tab">Builder</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact" data-toggle="tab">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="#social" data-toggle="tab">Social</a></li>
                    <li class="nav-item"><a class="nav-link" href="#emailserver" data-toggle="tab">Mail settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="#security" data-toggle="tab">Security</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="col-md-12 py-4">
                    <div id="resp"></div>
                    <div class="tab-content">
                        <div class="active tab-pane" role="tabpanel" id="website">
                            <form action="" method="post" enctype="multipart/form-data">
                                <h4>Web Site SEO</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="DOMAIN_SITE">DOMAIN SITE:</label>
                                    <input type="text" class="form-control" id="DOMAIN_SITE" name="DOMAIN_SITE" value="<?php echo $confs["DOMAIN_SITE"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_NAME">SITE NAME:</label>
                                    <input type="text" class="form-control" id="SITE_NAME" name="SITE_NAME" value="<?php echo $confs["SITE_NAME"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_BRAND_IMG">SITE BRAND IMG:</label>
                                    <input type="file" class="form-control" id="SITE_BRAND_IMG" name="SITE_BRAND_IMG" value="<?php echo $confs["SITE_BRAND_IMG"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_PATH;">SITE PATH:</label>
                                    <input type="text" class="form-control" id="SITE_PATH" name="SITE_PATH" value="<?php echo $confs["SITE_PATH"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_DESCRIPTION">SITE DESCRIPTION:</label>
                                    <textarea type="text" class="form-control" id="SITE_DESCRIPTION" name="SITE_DESCRIPTION"><?php echo $confs["SITE_DESCRIPTION"]; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="SITE_KEYWORDS">SITE KEYWORDS:</label>
                                    <textarea type="text" class="form-control" id="SITE_KEYWORDS" name="SITE_KEYWORDS"><?php echo $confs["SITE_KEYWORDS"]; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="SITE_CLASSIFICATION">SITE CLASSIFICATION:</label>
                                    <textarea type="text" class="form-control" id="SITE_CLASSIFICATION" name="SITE_CLASSIFICATION"><?php echo $confs["SITE_CLASSIFICATION"]; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="SITE_EMAIL">SITE EMAIL:</label>
                                    <input type="text" class="form-control" id="SITE_EMAIL" name="SITE_EMAIL" value="<?php echo $confs["SITE_EMAIL"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE IMAGE">SITE IMAGE:</label>
                                    <input type="file" class="form-control" id="SITE_IMAGE" name="SITE_IMAGE" value="<?php echo $confs["SITE_IMAGE"]; ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="controls">
                            <form method="post">
                                <h4>Site Settings</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="SITE_ADMIN">SITE ADMIN:</label>
                                    <input type="text" class="form-control" id="SITE_ADMIN" name="SITE_ADMIN" value="<?php echo $confs["SITE_ADMIN"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_CONTROL">SITE CONTROL:</label>
                                    <input type="text" class="form-control" id="SITE_CONTROL" name="SITE_CONTROL" value="<?php echo $confs["SITE_CONTROL"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_CONFIG">SITE CONFIG:</label>
                                    <input type="text" class="form-control" id="SITE_CONFIG" name="SITE_CONFIG" value="<?php echo $confs["SITE_CONFIG"]; ?>">
                                </div>                                           
                                <div class="form-group">
                                    <label for="SITE_LANGUAGE_1">SITE LANGUAGE 1:</label>
                                    <input type="text" class="form-control" id="SITE_LANGUAGE_1" name="SITE_LANGUAGE_1" value="<?php echo $confs["SITE_LANGUAGE_1"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_LANGUAGE_2">SITE LANGUAGE 2:</label>
                                    <input type="text" class="form-control" id="SITE_LANGUAGE_2" name="SITE_LANGUAGE_2" value="<?php echo $confs["SITE_LANGUAGE_2"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="FOLDER_IMAGES">FOLDER IMAGES:</label>
                                    <input type="text" class="form-control" id="FOLDER_IMAGES" name="FOLDER_IMAGES" value="<?php echo $confs["FOLDER_IMAGES"]; ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="builder">
                            <form method="post">
                                <h4>Builder and Editor</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="SITE_CREATOR">SITE CREATOR:</label>
                                    <input type="text" class="form-control" id="SITE_CREATOR" name="SITE_CREATOR" value="<?php echo $confs["SITE_CREATOR"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_EDITOR">SITE EDITOR:</label>
                                    <input type="text" class="form-control" id="SITE_EDITOR" name="SITE_EDITOR" value="<?php echo $confs["SITE_EDITOR"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SITE_BUILDER">SITE BUILDER:</label>
                                    <input type="text" class="form-control" id="SITE_BUILDER" name="SITE_BUILDER" value="<?php echo $confs["SITE_BUILDER"]; ?>">
                                </div>      
                                <div class="form-group">
                                    <label for="SITE_LIST">SITE LIST:</label>
                                    <input type="text" class="form-control" id="SITE_LIST" name="SITE_LIST" value="<?php echo $confs["SITE_LIST"]; ?>">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="contact">
                            <form method="post">
                                <h4>Contact Info</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="NAME_CONTACT">NAME CONTACT:</label>
                                    <input type="text" class="form-control" id="NAME_CONTACT" name="NAME_CONTACT" value="<?php echo $confs["NAME_CONTACT"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="PHONE_CONTACT">PHONE CONTACT:</label>
                                    <input type="text" class="form-control" id="PHONE_CONTACT" name="PHONE_CONTACT" value="<?php echo $confs["PHONE_CONTACT"]; ?>"> 
                                </div>
                                <div class="form-group">
                                    <label for="EMAIL_CONTACT">EMAIL CONTACT:</label>
                                    <input type="text" class="form-control" id="EMAIL_CONTACT" name="EMAIL_CONTACT" value="<?php echo $confs["EMAIL_CONTACT"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ADDRESS">ADDRESS:</label>
                                    <textarea type="text" class="form-control" id="ADDRESS" name="ADDRESS"><?php echo $confs["ADDRESS"]; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="social">
                            <form method="post">
                                <h4>Social media</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="TWITTER">TWITTER:</label>
                                    <input type="text" class="form-control" id="TWITTER" name="TWITTER" value="<?php echo $confs["TWITTER"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="FACEBOOKID">FACEBOOKID:</label>
                                    <input type="text" class="form-control" id="FACEBOOKID" name="FACEBOOKID" value="<?php echo $confs["FACEBOOKID"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SKYPE">SKYPE:</label>
                                    <input type="text" class="form-control" id="SKYPE" name="SKYPE" value="<?php echo $confs["SKYPE"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="TELEGRAM">TELEGRAM:</label>
                                    <input type="text" class="form-control" id="TELEGRAM" name="TELEGRAM" value="<?php echo $confs["TELEGRAM"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="WHATSAPP">WHATSAPP:</label>
                                    <input type="text" class="form-control" id="WHATSAPP" name="WHATSAPP" value="<?php echo $confs["WHATSAPP"]; ?>">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" role="tabpanel" id="emailserver">
                            <form method="post">
                                <h4>Email Settings</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="MAILSERVER">SMTP Mail Server:</label>
                                    <input type="text" class="form-control" id="MAILSERVER" name="MAILSERVER" value="<?php echo $confs["MAILSERVER"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="PORTSERVER">Port Server:</label>
                                    <input type="text" class="form-control" id="PORTSERVER" name="PORTSERVER" value="<?php echo $confs["PORTSERVER"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="USEREMAIL">Email Account:</label>
                                    <input type="text" class="form-control" id="USEREMAIL" name="USEREMAIL" value="<?php echo $confs["USEREMAIL"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="PASSMAIL">Password:</label>
                                    <input type="text" class="form-control" id="PASSMAIL" name="PASSMAIL" value="<?php echo $confs["PASSMAIL"]; ?>">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="security">
                            <form method="post">
                                <h4>Security and Admin</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="SUPERADMIN_NAME">SUPERADMIN NAME:</label>
                                    <input type="text" class="form-control" id="SUPERADMIN_NAME" name="SUPERADMIN_NAME" value="<?php echo $confs["SUPERADMIN_NAME"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SUPERADMIN_LEVEL">SUPERADMIN LEVEL:</label>
                                    <input type="text" class="form-control" id="SUPERADMIN_LEVEL" name="SUPERADMIN_LEVEL" value="<?php echo $confs["SUPERADMIN_LEVEL"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ADMIN_NAME">ADMIN NAME:</label>
                                    <input type="text" class="form-control" id="ADMIN_NAME" name="ADMIN_NAME" value="<?php echo $confs["ADMIN_NAME"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ADMIN_LEVEL">ADMIN LEVEL:</label>
                                    <input type="text" class="form-control" id="ADMIN_LEVEL" name="ADMIN_LEVEL" value="<?php echo $confs["ADMIN_LEVEL"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SECURE_HASH">SECURE HASH:</label>
                                    <input type="text" class="form-control" id="SECURE_HASH" name="SECURE_HASH" value="<?php echo $confs["SECURE_HASH"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SECURE_TOKEN">SECURE TOKEN:</label>
                                    <input type="text" class="form-control" id="SECURE_TOKEN" name="SECURE_TOKEN" value="<?php echo $confs["SECURE_TOKEN"]; ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</div>


