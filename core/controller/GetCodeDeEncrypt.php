<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class GetCodeDeEncrypt{
    public $characters;
    
    public function __construct(){
        $this->characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&@[]{|}';
    }

    // This function check that they do not have html symbols 
   public function procheck($string) {
    // Eliminar stripslashes() - es inseguro
    $str = htmlentities($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return htmlspecialchars(trim($str), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
    public function ende_crypter($action, $string, $secret_key, $secret_iv) {
    $encrypt_method = 'AES-256-GCM'; // Mejor que CBC
    
    // Usar derivación de clave más segura
    $key = openssl_pbkdf2($secret_key, $secret_iv, 32, 10000, 'sha256');
    
    if ($action === 'encrypt') {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encrypt_method));
        $tag = '';
        $encrypted = openssl_encrypt($string, $encrypt_method, $key, OPENSSL_RAW_DATA, $iv, $tag);
        return base64_encode($iv . $tag . $encrypted);
    } 
    
    if ($action === 'decrypt') {
        $data = base64_decode($string);
        $iv_len = openssl_cipher_iv_length($encrypt_method);
        $iv = substr($data, 0, $iv_len);
        $tag = substr($data, $iv_len, 16);
        $ciphertext = substr($data, $iv_len + 16);
        return openssl_decrypt($ciphertext, $encrypt_method, $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
}
/*
    public function ende_crypter($action, $string, $secret_key, $secret_iv) {
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
    */
// sha1(crypt(uniqid(), random_int(10000000, 99999999))); // Get 40 string
// sha1(bin2hex(mt_rand())); // Get 40 string
// sha1(uniqid(mt_rand(), TRUE)); // Get 40 string
// randtoken maker
public function randToken() {
        return substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(64))), 0, 64);
    }

// randkey maker
public function randKey() {
        return substr(str_replace(['+', '/', '='], '', base64_encode(openssl_random_pseudo_bytes(64))), 0, 64);
    }

// randhash maker
public function randHash() {      
        return bin2hex(substr(str_replace(['+', '/', '='], '', base64_encode(openssl_random_pseudo_bytes(32))), 0, 32));
    }
public function iRandHash() {
        $len = 64;
        $secret = substr(sha1(openssl_random_pseudo_bytes(19)), - $len) . sha1(openssl_random_pseudo_bytes(15));
        return substr(hash('sha256', $secret), 0, $len);
    }

public function iRandKey($length) {   
        $characters = $this->characters;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
// Generate random code for usercode = 64 strings
public function getRandomString(int $length, bool $crypto_secure = true): string {
    $characters = $this->characters;
    $max = strlen($characters) - 1;
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        if ($crypto_secure && function_exists('random_int')) {
            $randomString .= $characters[random_int(0, $max)];
        } else {
            $randomString .= $characters[mt_rand(0, $max)];
        }
    }
    return $randomString;
}
/*
public function getRandomString($length) {
    $characters = $this->characters;
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
*/
public function getRandKey()
{
    $length = 64;
    if (function_exists('random_bytes')) {
        $bytes = random_bytes($length / 2);
    } else {
        $bytes = openssl_random_pseudo_bytes($length / 2);
    }
    return bin2hex($bytes);
}

 public function getRandomCode() {
        $n = 56;
        $characters = $this->characters;
        $randomString = '';
        $cod = rand(10000000, 99999999);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
    return $randomString . $cod; // return 64
    // string
}

public function getRandCode(){
$len = 64;
$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len); // return 64 string
}

public function getKeyCode(){
    return bin2hex(random_bytes(32)); // return 64 strings
}
public function getIdCode(){
    return bin2hex(openssl_random_pseudo_bytes(32)); //return 64 strings
}
public function randString($leng) {      
    return bin2hex(openssl_random_pseudo_bytes($leng)); //return double to length strings
}
public function randLengthString($len) {      
        $secret = substr(sha1(openssl_random_pseudo_bytes(17)), - $len) . sha1(openssl_random_pseudo_bytes(13));
        return substr(hash('sha256', $secret), 0, $len);
    }
public function generateRandStr($length) {
        $randstr = "";
        for ($i = 0; $i < $length; $i++) {
            $randnum = mt_rand(0, 61);
            if ($randnum < 10) {
                $randstr .= chr($randnum + 53);
            } else if ($randnum < 36) {
                $randstr .= chr($randnum + 49);
            } else {
                $randstr .= chr($randnum + 61);
            }
        }
        return $randstr;
    }
    // Método unificado para reemplazar todos
public function generateSecureToken(int $length = 64, string $mode = 'hex'): string {
    $bytes = $this->getSecureBytes($length);
    
    return match($mode) {
        'hex' => bin2hex($bytes),
        'base64' => substr(str_replace(['+', '/', '='], '', base64_encode($bytes)), 0, $length),
        'hash' => substr(hash('sha256', $bytes), 0, $length),
        'alphanum' => $this->bytesToAlphanumeric($bytes, $length),
        default => bin2hex($bytes)
    };
}

private function getSecureBytes(int $length): string {
    return function_exists('random_bytes') 
        ? random_bytes($length) 
        : openssl_random_pseudo_bytes($length);
}
/**
 * Genera un string aleatorio criptográficamente seguro
 * 
 * @param int $length Longitud deseada (1-1024)
 * @param string $alphabet Caracteres permitidos (opcional)
 * @return string
 * @throws InvalidArgumentException Si la longitud es inválida
 */
public function generateSecureRandomString(int $length, ?string $alphabet = null): string {
    if ($length < 1 || $length > 1024) {
        throw new InvalidArgumentException('Length must be between 1 and 1024');
    }
    
    $alphabet = $alphabet ?? $this->charSet;
    $alphabetLength = strlen($alphabet);
    
    if ($alphabetLength < 2) {
        throw new InvalidArgumentException('Alphabet must contain at least 2 characters');
    }
    
    $result = '';
    $maxIndex = $alphabetLength - 1;
    
    for ($i = 0; $i < $length; $i++) {
        $result .= $alphabet[random_int(0, $maxIndex)];
    }
    
    return $result;
}
}