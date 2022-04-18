<?php

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
        $def = fopen($definefiles, 'w');
        if (!$def) {
            $_SESSION['ErrorMessage'] = 'Error creating the file ' . $definefiles;
        }

        $ndef = '<?php' . "\n";
        $ndef .= implode("\n ", $fldname);
        $ndef .= '?>' . "\n";
        file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);

        $_SESSION['SuccessMessage'] = "The configuration definitions file has been created ";
    }
}

