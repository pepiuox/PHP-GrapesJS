<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RecoveryPhrase
 *
 * @author PePiuoX
 */
class RecoveryPhrase {

    public $connection;

    //put your code here
    public function __construct() {
        global $conn;
        $this->connection = $conn;
        if (isset($_POST["makerecoveryphrase"])) {
            $this->MakeRecoveryPhrase();
        }
        if (isset($_POST["updaterecoveryphrase"])) {
            $this->UpdateRecoveryPhrase();
        }
    }

    private function ende_crypter($action, $string, $secret_key, $secret_iv) {
        $output = false;
        $encrypt_method = 'AES-256-CBC';

// hash
        $key = hash('sha256', $secret_key);
// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    private function MakeRecoveryPhrase() {
// Require credentials for DB connection.

        if (isset($_POST['makerecoveryphrase'])) {

            if (!empty($_POST['pin']) && !empty($_POST['rvphrase'])) {
                $username = $_SESSION['user_id'];
                $level = $_SESSION['levels'];
                $rvphrase = $_POST['rvphrase'];
                $userpin = $_POST['pin'];
                $cnull = 0;

                $result = $this->connection->prepare("SELECT * FROM uverify WHERE username = ? AND mkpin = ? AND level = ? AND rp_active = ?");
                $result->bind_param("sssi", $username, $userpin, $level, $cnull);
                $result->execute();
                $resu = $result->get_result();
                $urw = $resu->fetch_assoc();

                if ($resu->num_rows === 1) {
                    $secret_key = $urw['mktoken'];
                    $secret_iv = $urw['mkkey'];
                    $crvp = $this->ende_crypter('encrypt', $rvphrase, $secret_key, $secret_iv);
                    $rpac = 1;

                    $update = $this->connection->prepare("UPDATE uverify SET recovery_phrase = ?, rp_active = ? WHERE username = ? AND mkpin = ? AND level = ?");
                    $update->bind_param("sisss", $crvp, $rpac, $username, $userpin, $level);
                    $update->execute();
                    $nupd = $update->affected_rows;
                    $update->close();
                    if ($nupd === 1) {
                        unset($_SESSION['AlertMessage']);
                        unset($_SESSION['RecoveryMessage']);
                        $_SESSION['SuccessMessage'] = 'Gracias su cuenta ahora es más segura.';
                    } else {
                        $_SESSION['ErrorMessage'] = 'Error problemas con actualizar de datos.';
                    }
                } else {
                    $_SESSION['ErrorMessage'] = 'Error problemas con verificación de datos.';
                }
                $result->close();
            } else {
                $_SESSION['ErrorMessage'] = 'Complete correctamente las casillas.';
            }
        }
    }

    private function UpdateRecoveryPhrase() {
// Require credentials for DB connection.

        if (isset($_POST['updaterecoveryphrase'])) {
            $rvphrase = $_POST['rvphrase'];
            $userpin = $_POST['pin'];
            $username = $_SESSION['user_id'];
            $level = $_SESSION['levels'];
            $cnull = 'NULL';
            if (!empty($username) && !empty($userpin) && !empty($rvphrase)) {
                $result = $this->connection->prepare(" SELECT * FROM uverify WHERE username = ? AND mkpin = ? AND level = ? AND activation_code = ?");
                $result->bind_param("ss", $username, $userpin, $level, $cnull);
                $result->execute();
                $num = $result->affected_rows;
                $urw = $result->fetch_assoc();
                $result->close();
                $secret_key = $urw['mktoken'];
                $secret_iv = $urw['mkkey'];

                $crvp = $this->ende_crypter('encrypt', $rvphrase, $secret_key, $secret_iv);

                if ($num === 1) {
                    $rpac = 1;
                    $update = $this->connection->prepare("UPDATE uverify SET recovery_phrase=?, rp_active=? WHERE username=? AND mkpin=? AND level = ?");
                    $update->bind_param("sisss", $crvp, $rpac, $username, $userpin, $level);
                    $update->execute();
                    if ($update === TRUE) {
                        unset($_SESSION['AlertMessage']);
                        $_SESSION['SuccessMessage'] = 'Gracias su cuenta ahora es más segura.';
                        header('Location: index.php');
                    }
                }
            }
        }
    }

}
