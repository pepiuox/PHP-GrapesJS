<?php

// Fourth step
// Define configuration for the website
function RandHash($len = 128) {

    $secret = substr(sha1(openssl_random_pseudo_bytes(21)), - $len) . sha1(openssl_random_pseudo_bytes(13));
    return substr(hash('sha256', $secret), 0, $len);
}

function RandKey($length = 128) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ}#[$)%&{]@(';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

extract($_POST);
if (isset($_POST['Update'])) {
    $definefiles = '../config/define.php';
    if (file_exists($definefiles)) {
        unlink($definefiles);
    }
    $_SESSION['AlertMessage'] = "The definitions are up to date.";

    foreach ($_POST as $k => $v) {
        if ($_POST['Update'] === $v) {
            continue;
        }
        if ($k == 'DOMAIN_SITE' || $k == 'SITE_PATH') {
            $v .= '/';
        }

        $vals[] = "`" . $k . "` = '" . $v . "'";
    }
    $vupdates = implode(", ", $vals);
    $update = ("UPDATE site_configuration SET $vupdates WHERE `ID_Site` = '1'");
    if ($conn->query($update) === TRUE) {
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
                $fldname[] = "define('" . $val->name . "','" . $fdata[$val->name] . "');";
            }

            if (!file_exists($definefiles)) {

                $ndef = '<?php' . "\n";
                $ndef .= implode("\n ", $fldname);
                $ndef .= '?>' . "\n";

                file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);

                $_SESSION['SuccessMessage'] = "The configuration definitions file has been created ";

                $_SESSION['StepInstall'] = 5;
                header("Location: install.php?step=5");
                exit();
            }
        }
    } else {
        $_SESSION['ErrorMessage'] = 'Error Updating configutations.';
    }
    $conn->close();
}
?>
<div class="alert alert-success" role="alert">
        <h5>Create configuration for your web site</h5>
        </div>
        <div class="mb-3">
        <div class="alert alert-primary text-center" role="alert">
        <h3>4.- Fourth step</h3>
        </div>
        <h4>Define the website values .</h4>
        <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75"
        aria-valuemin="0" aria-valuemax="100">75%</div>
        </div>
        </div>
        <h4>Define values for the configuration</h4>


        <hr>
        <div class="form-group">
        <label for="DOMAIN_SITE">DOMAIN SITE:</label>
        <input type="text" class="form-control" id="DOMAIN_SITE" name="DOMAIN_SITE"
        value="<?php echo $confs["DOMAIN_SITE"]; ?>">
        </div>
        <div class="form-group">
        <label for="SITE_NAME">SITE NAME:</label>
        <input type="text" class="form-control" id="SITE_NAME" name="SITE_NAME"
        value="<?php echo $confs["SITE_NAME"]; ?>">
        </div>
        <div class="form-group">
        <label for="SITE_PATH">SITE PATH:</label>
        <input type="text" class="form-control" id="SITE_PATH" name="SITE_PATH"
        value="<?php echo $siteinstall; ?>">
        </div>
        <hr>
        <h5>Secure installs strings</h5>
        <div class="form-group">
        <label for="SECURE_HASH">SECURE HASH:</label>
        <input type="text" class="form-control" id="SECURE_HASH" name="SECURE_HASH"
        value="<?php echo RandHash(); ?>" readonly="yes">
        </div>
        <div class="form-group">
        <label for="SECURE_TOKEN">SECURE TOKEN:</label>
        <textarea class="form-control" id="SECURE_TOKEN" name="SECURE_TOKEN"
        readonly="yes"><?php echo RandKey(); ?></textarea>
        </div>
        <div class="col-12">
        <button type="submit" name="Update" class="btn btn-primary">Save</button>
        </div>
