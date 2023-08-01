<?php
if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {

    $valueCount = count($_POST["config_name"]);
    for ($i = 0; $i < $valueCount; $i++) {
        $conn->query("UPDATE configuration SET  `config_value` =  '{$_POST['config_value'][$i]}'   WHERE `config_name` = '{$_POST['config_name'][$i]}' ");
    }

    $define = $conn->query("SELECT * FROM configuration");
    while ($rowt = $define->fetch_array()) {
        $values = $rowt['config_value'];
        $names = $rowt['config_name'];
        $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
    }

    $definefiles = '../config/define.php';

    if (!file_exists($definefiles)) {
        $ndef = '<?php' . "\n";
        $ndef .= implode(" ", $vars);
        $ndef .= '?>' . "\n";
        file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
    } else {
        unlink($definefiles);
        $ndef = '<?php' . "\n";
        $ndef .= implode(" ", $vars);
        $ndef .= '?>' . "\n";
        file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
    }
}

$result = $conn->query("SELECT * FROM `site_configuration` WHERE `ID_Site` = '1'") or trigger_error($conn->error);
$confs = $result->fetch_assoc();

extract($_POST);

if (isset($_POST['Update'])) {
    $definefiles = '../config/define.php';
        if (file_exists($definefiles)) {
            unlink($definefiles);
        }
    
    foreach ($_POST as $k => $v) {
        if ($_POST['Update'] === $v) {
            continue;
        }
        $vals[] = "`" . $k . "` = '" . $v . "'";
    }
    $vupdates = implode(", ", $vals);
    $update = ("UPDATE site_configuration SET $vupdates WHERE `ID_Site` = '1'");
    if ($conn->query($update) === TRUE) {
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
                $definefiles = '../config/define.php';
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
                            <form method="post" enctype="multipart/form-data">
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
                                    <input type="FILE" class="form-control" id="SITE_BRAND_IMG" name="SITE_BRAND_IMG" value="<?php echo $confs["SITE_BRAND_IMG"]; ?>">
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


