<?php
include_once '../controller/CryptoGenerator.php';

 $crypto = new CryptoGenerator();
// Generar diferentes tipos de strings
  $token = $crypto->token();                    // Token CSRF
  $apiKey = $crypto->apiKey();                  // API Key
  $hash = $crypto->secureHash();                // Hash seguro
  $uuid = $crypto->uuid();                      // UUID v4
  $code = $crypto->verificationCode(6);         // Código de 6 dígitos

  // Generar con opciones específicas
  $hexString = $crypto->random(32, 'hex');      // String hexadecimal
  $base64String = $crypto->random(48, 'base64'); // Base64 URL-safe
  $custom = $crypto->customString(50, 'ABC123'); // Solo caracteres personalizados

  // Cifrar/Descifrar datos sensibles
  $secretKey = 'mi-clave-super-segura-123';
  $data = 'Información confidencial';

  $encrypted = $crypto->encrypt($data, $secretKey);
  $decrypted = $crypto->decrypt($encrypted, $secretKey);

  // Sanitizar entradas de usuario
  $userInput = '<script>alert("xss")</script> Hola mundo';
  $clean = $crypto->sanitize($userInput); // &lt;script&gt;...

  // Validar email
  $email = $crypto->sanitizeEmail('usuario@ejemplo.com');
  if ($email) {
      echo "Email válido: $email";
 }

