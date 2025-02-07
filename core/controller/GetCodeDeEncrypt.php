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
public function getRandomString($length) {
    $characters = $this->characters;
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

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
}
