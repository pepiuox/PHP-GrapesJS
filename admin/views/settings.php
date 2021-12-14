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
$row = $result->fetch_assoc()

?>
<div class="container">            
    <div class="row">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
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
                        <div class="active tab-pane" id="website">
                            <form>
                                <h4>Web Site Configuration</h4>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Domain site</span>
                                    <input type="text" aria-label="Domain site" class="form-control" value="<?php $row['config_value']; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site name</span>
                                    <input type="text" aria-label="Site name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <span class="input-group-text" for="formFile">Site brand</span>
                                    <input class="form-control" type="file" id="formFile" aria-label="Site name">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site description</span>
                                    <textarea class="form-control" aria-label="Site description"></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site keywords</span>
                                    <textarea class="form-control" aria-label="Site keywords"></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site Clasification</span>
                                    <textarea class="form-control" aria-label="Site keywords"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="controls">
                            <form>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site admin</span>
                                    <input type="text" aria-label="Site admin" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site control</span>
                                    <input type="text" aria-label="Site control" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site config</span>
                                    <input type="text" class="form-control" aria-label="Site config">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site list</span>
                                    <input type="text" class="form-control" aria-label="Site list">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="builder">
                            <form>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site author</span>
                                    <input type="text" aria-label="Site author" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site editor</span>
                                    <input type="text" aria-label="Site editor" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site builder</span>
                                    <input type="text" aria-label="Site builder" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Folder Images</span>
                                    <input type="text" aria-label="Folder Images" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site language</span>
                                    <select class="form-select" aria-label="Site language">
                                        <option selected>Your language</option>
                                        <option value="1">English</option>
                                        <option value="2">Spanish</option>

                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Site email</span>
                                    <input type="text" class="form-control" aria-label="Site email">
                                </div>          
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="contact">
                            <form>
                                <h4>Contact Info</h4>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contact name</span>
                                    <input type="text" aria-label="Contact name" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Phone contact</span>
                                    <input type="text" aria-label="Phone contact" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Email contact</span>
                                    <input type="text" aria-label="Email contact" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Business address</span>
                                    <textarea class="form-control" aria-label="Business address"></textarea>
                                </div> 
                                <hr>
                                <h4>Social media</h4>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Twitter address</span>
                                    <input type="text" class="form-control" aria-label="twitter address">
                                </div> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text">facebook address</span>
                                    <input type="text" class="form-control" aria-label="Facebook address">
                                </div> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Telegram address</span>
                                    <input type="text" class="form-control" aria-label="Telegram address">
                                </div> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Whatsapp</span>
                                    <input type="text" class="form-control" aria-label="Whatsapp">
                                </div> 
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="security">
                            <form>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Super Admin name</span>
                                    <input type="text" aria-label="Super Admin name" class="form-control">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Super Admin level</span>
                                    <input type="text" aria-label="Super Admin level" class="form-control">
                                </div>                               
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Admin name</span>
                                    <input type="text" class="form-control" aria-label="Admin name">
                                </div>    
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Admin level</span>
                                    <input type="text" class="form-control" aria-label="Admin level">
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
