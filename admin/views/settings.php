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

$result = $conn->query("SELECT * FROM `configuration`") or trigger_error($conn->error);
$confs = array();
while ($row = $result->fetch_array()) {
    $confs[] = $row['config_value'];
}
extract($_POST);
if (isset($Submit)) {
    for ($i = 0; $i < $count; $i++) {
        $update = ("UPDATE configuration SET config_value='$value[$i]' WHERE id='$id[$i]'");
        $res = mysql_query($update);
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
                    <li class="nav-item"><a class="nav-link" href="#security" data-toggle="tab">Security</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="col-md-12 py-4">
                    <div id="resp"></div>
                    <div class="tab-content">
                        <div class="active tab-pane" role="tabpanel" id="website">
                            <form method="post">
                                <h5>Web Site SEO</h5>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Domain site</span>
                                    <input type="text" aria-label="Domain site" class="form-control" value="<?php echo $confs[0]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site name</span>
                                    <input type="text" aria-label="Site name" class="form-control" value="<?php echo $confs[1]; ?>">
                                </div>
                                <div class="mb-3">
                                    <span class="input-group-text" for="formFile">Site brand</span>
                                    <input class="form-control" type="file" id="formFile" aria-label="Site name" value="<?php echo $confs[2]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site description</span>
                                    <textarea class="form-control" aria-label="Site description"><?php echo $confs[3]; ?></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site keywords</span>
                                    <textarea class="form-control" aria-label="Site keywords"><?php echo $confs[4]; ?></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site Clasification</span>
                                    <textarea class="form-control" aria-label="Site keywords"><?php echo $confs[5]; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="controls">
                            <form method="post">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site admin</span>
                                    <input type="text" aria-label="Site admin" class="form-control" value="<?php echo $confs[6]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site control</span>
                                    <input type="text" aria-label="Site control" class="form-control" value="<?php echo $confs[7]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site config</span>
                                    <input type="text" class="form-control" aria-label="Site config" value="<?php echo $confs[8]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site list</span>
                                    <input type="text" class="form-control" aria-label="Site list" value="<?php echo $confs[9]; ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="builder">
                            <form method="post">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site author</span>
                                    <input type="text" aria-label="Site author" class="form-control" value="<?php echo $confs[10]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site editor</span>
                                    <input type="text" aria-label="Site editor" class="form-control" value="<?php echo $confs[11]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site builder</span>
                                    <input type="text" aria-label="Site builder" class="form-control" value="<?php echo $confs[12]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Folder Images</span>
                                    <input type="text" aria-label="Folder Images" class="form-control" value="<?php echo $confs[13]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site language</span>
                                    <select class="form-select" aria-label="Site language">
                                        <option selected>Your language</option>
<?php echo $confs[14]; ?>
                                        <option value="1">English</option>
                                        <option value="2">Spanish</option>

                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site email</span>
                                    <input type="text" class="form-control" aria-label="Site email" value="<?php echo $confs[15]; ?>">
                                </div>          
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="contact">
                            <form method="post">
                                <h4>Contact Info</h4>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contact name</span>
                                    <input type="text" aria-label="Contact name" class="form-control" value="<?php echo $confs[16]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Phone contact</span>
                                    <input type="text" aria-label="Phone contact" class="form-control" value="<?php echo $confs[17]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Email contact</span>
                                    <input type="text" aria-label="Email contact" class="form-control" value="<?php echo $confs[18]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Business address</span>
                                    <textarea class="form-control" aria-label="Business address"><?php echo $confs[19]; ?></textarea>
                                </div> 
                                <hr>
                                <h4>Social media</h4>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Twitter address</span>
                                    <input type="text" class="form-control" aria-label="twitter address" value="<?php echo $confs[20]; ?>">
                                </div> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text">facebook address</span>
                                    <input type="text" class="form-control" aria-label="Facebook address" value="<?php echo $confs[21]; ?>">
                                </div> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Telegram address</span>
                                    <input type="text" class="form-control" aria-label="Telegram address" value="<?php echo $confs[22]; ?>">
                                </div> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Whatsapp</span>
                                    <input type="text" class="form-control" aria-label="Whatsapp" value="<?php echo $confs[23]; ?>">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="security">
                            <form method="post">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Super Admin name</span>
                                    <input type="text" aria-label="Super Admin name" class="form-control" value="<?php echo $confs[24]; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Super Admin level</span>
                                    <input type="text" aria-label="Super Admin level" class="form-control" value="<?php echo $confs[25]; ?>">
                                </div>                               
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Admin name</span>
                                    <input type="text" class="form-control" aria-label="Admin name" value="<?php echo $confs[26]; ?>">
                                </div>    
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Admin level</span>
                                    <input type="text" class="form-control" aria-label="Admin level" value="<?php echo $confs[27]; ?>">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</div>


