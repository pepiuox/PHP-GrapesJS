<?php
global $conn;
$result = $conn->query("SELECT * FROM `site_configuration` WHERE `ID_Site` = '1'");
$confs = $result->fetch(PDO::FETCH_ASSOC);

/*
 * Upload images before update table
 */

function UploadImage($image) {
    global $conn;
    // Check if a file was actually sent for this field
    if (!empty($_FILES[$image]["name"])) {
        if (isset($_FILES[$image])) {

            // directory name to store the uploaded image files
            // this should have sufficient read/write/execute permissions
            // if not already exists, please create it in the root of the
            // project folder
            $targetDir = "../uploads/";
            // Validate and save with a server-generated name: the client filename is
            // only used to read the extension, never to build the destination path.
            $upload_error = null;
            $nimage = \PepiuoX\Security\Upload::storeImage($_FILES[$image], $targetDir, 2000000, $upload_error);

            // Check if the upload was rejected
            if ($nimage === null) {
                $_SESSION['ErrorMessage'] = "Sorry, your file was not uploaded.";
                if ($upload_error !== null) {
                    $_SESSION['ErrorMessage'] .= " " . $upload_error;
                }
            } else {
                $id = 1;
                $image = \PepiuoX\Security\SqlIdentifier::assert($image);
                $upim = $conn->prepare("UPDATE site_configuration SET $image = ? WHERE `ID_Site` = ?");
                $upim->execute([$nimage, $id]);
                $upim->closeCursor();

                $_SESSION['SuccessMessage'] = "The file " . htmlspecialchars($nimage, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . " has been uploaded.";
            }
        }
    }
}

extract($_POST, EXTR_SKIP); // ✅ EXTR_SKIP: no sobrescribe variables locales

if (isset($_POST['Update'])) {
    if ($_FILES) {
        extract($_FILES, EXTR_SKIP); // ✅ EXTR_SKIP: no sobrescribe variables locales
        foreach ($_FILES as $k => $v) {
            $v = $k;
            UploadImage($v);
        }
    }

    /* update table */
    $valsParams = [];
    foreach ($_POST as $k => $v) {
        if ($_POST['Update'] === $v) {
            continue;
        }
        // ✅ Excluir el campo CSRF: no es una columna de la tabla.
        if ($k === 'csrf_token') {
            continue;
        }
        $k = \PepiuoX\Security\SqlIdentifier::assert($k);
        $vals[] = "`" . $k . "` = :" . $k;
        $valsParams[":" . $k] = $v;
    }
    $vupdates = implode(", ", $vals);
    $update = ("UPDATE site_configuration SET $vupdates WHERE `ID_Site` = '1'");

    $stmt = $conn->prepare($update);
    if ($stmt->execute($valsParams) !== false) {
        $definefiles = '../config/define.php';

        $_SESSION['SuccessMessage'] = "Web Site Configuration : Updated.";
        $sql = "SELECT * FROM site_configuration WHERE `ID_Site` = '1'";

        if ($result = $conn->query($sql)) {
            $fdata = $result->fetch(PDO::FETCH_ASSOC);
            $fname = array_map(function ($k) { return (object) array('name' => $k); }, array_keys((array) $fdata));

            foreach ($fname as $val) {
                if ($val->name === 'ID_Site') {
                    continue;
                } elseif ($val->name === 'created_at') {
                    continue;
                } elseif ($val->name === 'updated_at') {
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
        header("Location: ../siteconf");
        exit;
    }
}
if (isset($_POST['UpdateSecures'])) {
    if ($_FILES) {
        extract($_FILES, EXTR_SKIP); // ✅ EXTR_SKIP: no sobrescribe variables locales
        foreach ($_FILES as $k => $v) {
            $v = $k;
            UploadImage($v);
        }
    }

    /* update table */
    $valsParams = [];
    foreach ($_POST as $k => $v) {
        if ($_POST['UpdateSecures'] === $v) {
            continue;
        }
        // ✅ Excluir el campo CSRF: no es una columna de la tabla.
        if ($k === 'csrf_token') {
            continue;
        }
        $k = \PepiuoX\Security\SqlIdentifier::assert($k);
        $vals[] = "`" . $k . "` = :" . $k;
        $valsParams[":" . $k] = $v;
    }
    $vupdates = implode(", ", $vals);
    $update = ("UPDATE site_security SET $vupdates WHERE `idSs` = '1'");

    $stmt = $conn->prepare($update);
    if ($stmt->execute($valsParams) !== false) {
        $definefiles = '../config/define.php';

        $_SESSION['SuccessMessage'] = "Web Site Secures Configuration : Updated.";
        $sql = "SELECT * FROM site_secures WHERE `idSs` = '1'";

        if ($result = $conn->query($sql)) {
            $fdata = $result->fetch(PDO::FETCH_ASSOC);
            $fname = array_map(function ($k) { return (object) array('name' => $k); }, array_keys((array) $fdata));

            foreach ($fname as $val) {
                if ($val->name === 'idSs') {
                    continue;
                } elseif ($val->name === 'site') {
                    continue;
                } elseif ($val->name === 'created_at') {
                    continue;
                } elseif ($val->name === 'updated_at') {
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
        header("Location: ../siteconf");
        exit;
    }
}

$result1 = $conn->query("SELECT * FROM `site_security` WHERE `idSs` = '1'");
$confsm = $result1->fetch(PDO::FETCH_ASSOC);
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
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                                    <button type="submit" name="Update" class="btn btn-primary">Save site info</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="controls">
                            <form method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                                    <button type="submit" name="Update" class="btn btn-primary">Save site controls</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="builder">
                            <form method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                                    <button type="submit" name="Update" class="btn btn-primary">Save build editor</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="contact">
                            <form method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                                    <button type="submit" name="Update" class="btn btn-primary">Save site contact</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="social">
                            <form method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
                                <h4>Social media</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="TWITTER">TWITTER:</label>
                                    <input type="text" class="form-control" id="TWITTER" name="TWITTER" value="<?php echo $confs["TWITTER"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="FACEBOOKID">FACEBOOKID:</label>
                                    <input type="text" class="form-control" id="FACEBOOKID" name="FACEBOOKID" value="<?php echo $confs["FACEBOOKID"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SKYPE">SKYPE:</label>
                                    <input type="text" class="form-control" id="SKYPE" name="SKYPE" value="<?php echo $confs["SKYPE"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="TELEGRAM">TELEGRAM:</label>
                                    <input type="text" class="form-control" id="TELEGRAM" name="TELEGRAM" value="<?php echo $confs["TELEGRAM"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="WHATSAPP">WHATSAPP:</label>
                                    <input type="text" class="form-control" id="WHATSAPP" name="WHATSAPP" value="<?php echo $confs["WHATSAPP"] ?? ''; ?>">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" name="Update" class="btn btn-primary">Save social media</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" role="tabpanel" id="emailserver">
                            <form method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
                                <h4>Email Settings</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="MAILSERVER">SMTP Mail Server:</label>
                                    <input type="text" class="form-control" id="MAILSERVER" name="MAILSERVER" value="<?php echo $confsm["MAILSERVER"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="PORTSERVER">Port Server:</label>
                                    <input type="text" class="form-control" id="PORTSERVER" name="PORTSERVER" value="<?php echo $confsm["PORTSERVER"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="USEREMAIL">Email Account:</label>
                                    <input type="text" class="form-control" id="USEREMAIL" name="USEREMAIL" value="<?php echo $confsm["USEREMAIL"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="PASSMAIL">Password:</label>
                                    <input type="text" class="form-control" id="PASSMAIL" name="PASSMAIL" value="<?php echo $confsm["PASSMAIL"] ?? ''; ?>">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" name="UpdateSecures" class="btn btn-primary">Save mail settings</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="security">
                            <form method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
                                <h4>Security and Admin</h4>
                                <hr>
                                <div class="form-group">
                                    <label for="SUPERADMIN_NAME">SUPERADMIN NAME:</label>
                                    <input type="text" class="form-control" id="SUPERADMIN_NAME" name="SUPERADMIN_NAME" value="<?php echo $confsm["SUPERADMIN_NAME"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SUPERADMIN_LEVEL">SUPERADMIN LEVEL:</label>
                                    <input type="text" class="form-control" id="SUPERADMIN_LEVEL" name="SUPERADMIN_LEVEL" value="<?php echo $confsm["SUPERADMIN_LEVEL"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ADMIN_NAME">ADMIN NAME:</label>
                                    <input type="text" class="form-control" id="ADMIN_NAME" name="ADMIN_NAME" value="<?php echo $confsm["ADMIN_NAME"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ADMIN_LEVEL">ADMIN LEVEL:</label>
                                    <input type="text" class="form-control" id="ADMIN_LEVEL" name="ADMIN_LEVEL" value="<?php echo $confsm["ADMIN_LEVEL"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SECURE_HASH">SECURE HASH:</label>
                                    <input type="text" class="form-control" id="SECURE_HASH" name="SECURE_HASH" value="<?php echo $confsm["SECURE_HASH"] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="SECURE_TOKEN">SECURE TOKEN:</label>
                                    <input type="text" class="form-control" id="SECURE_TOKEN" name="SECURE_TOKEN" value="<?php echo $confsm["SECURE_TOKEN"] ?? ''; ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="UpdateSecures" class="btn btn-primary">Save secure settings</button>
                                </div>
                            </form>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    <script>
        $(document).ready(function() {  
            
            $(".tab-pane").hide(); //Hide all content
            $("ul.nav-tabs li:first").addClass("active").show(); //Activate first tab
            $(".tab-pane:first").show(); //Show first tab content
                                       
            $("ul.nav-tabs li").click(function() {
                           
    		$("ul.nav-tabs li").find("a").removeClass("active"); //Remove any "active" class
                $(this).find("a").addClass("active"); //Add "active" class to selected tab
                $(this).show();    		
    		$(".tab-pane").hide(); //Hide all tab content
                //Find the href attribute value to identify the active tab + content
    		var activeTab = $(this).find("a").attr("href");                                
                $(activeTab).addClass("active").show();                
    		$(activeTab).fadeIn(); //Fade in the active ID content                
    		return false;
            });
        });
        
    </script>
    </div>
</div>


