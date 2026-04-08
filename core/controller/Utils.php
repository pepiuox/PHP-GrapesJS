<?php
class Utils {
    
    // Encriptar datos
    public static function encrypt($data) {
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_KEY), 0, 16);
        $output = openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
        return base64_encode($output);
    }
    
    // Desencriptar datos
    public static function decrypt($data) {
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_KEY), 0, 16);
        return openssl_decrypt(base64_decode($data), ENCRYPTION_METHOD, $key, 0, $iv);
    }
    
    // Generar slug
    public static function generateSlug($string) {
        $slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower($string));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    // Validar email
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    // Sanitizar entrada
    public static function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = self::sanitize($value);
            }
        } else {
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }
    
    // Generar token CSRF
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    // Verificar token CSRF
    public static function verifyCSRFToken($token) {
        if (!empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        return false;
    }
}