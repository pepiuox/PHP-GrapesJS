<?php
declare(strict_types=1);

/**
 * Script para añadir productos al carrito.
 *
 * CORRECCIONES:
 * - Validación de sesión
 * - CSRF protection
 * - Validación de inputs
 * - Prepared statements
 * - Tipado estricto
 * - Manejo de errores
 */

// ✅ Validar método HTTP (debe ser POST para CSRF)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

// ✅ CSRF validation
if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
    $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit;
}

// ✅ Validar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION["client_session"])) {
    $_SESSION['ErrorMessage'] = 'Sesión no válida. Por favor, inicie sesión.';
    header('Location: /signin/login');
    exit;
}

// ✅ Validar y sanitizar inputs
$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
if ($cantidad === false || $cantidad === null || $cantidad <= 0) {
    $_SESSION['ErrorMessage'] = 'Cantidad inválida';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit;
}

$producto_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($producto_id === false || $producto_id === null || $producto_id <= 0) {
    $_SESSION['ErrorMessage'] = 'ID de producto inválido';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit;
}

// ✅ Limitar cantidad máxima
$cantidad = min($cantidad, 999);

$secl = $_SESSION["client_session"];

try {
    // ✅ Obtener conexión PDO
    $db = new Database();
    $dbprd = $db->PdoConnection('ecommerce');

    // ✅ Verificar que el producto existe
    $checkProduct = $dbprd->prepare("SELECT idPrd FROM productos WHERE idPrd = :id AND active = 1");
    $checkProduct->execute([':id' => $producto_id]);

    if ($checkProduct->rowCount() === 0) {
        $_SESSION['ErrorMessage'] = 'El producto no existe o no está disponible';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    // ✅ Inicializar objeto del carrito
    $cart_item = new AddCart($dbprd);
    $cart_item->session_key = $secl;
    $cart_item->producto_id = $producto_id;
    $cart_item->cantidad = $cantidad;

    // ✅ Verificar si ya existe en el carrito
    if ($cart_item->exists()) {
        // ✅ Actualizar cantidad si ya existe
        $cart_item->update();
        $_SESSION['SuccessMessage'] = 'Cantidad actualizada en el carrito';
    } else {
        // ✅ Añadir nuevo item
        if (isset($_SESSION["client_id"]) && !empty($_SESSION["client_id"])) {
            // Usuario logueado
            $cart_item->cliente_id = (int) $_SESSION["client_id"];
            $result = $cart_item->createUser();
        } else {
            // Sesión de invitado
            $result = $cart_item->createSession();
        }

        if ($result) {
            $_SESSION['SuccessMessage'] = 'Producto añadido al carrito';
        } else {
            $_SESSION['ErrorMessage'] = 'Error al añadir el producto al carrito';
        }
    }

    // ✅ Redirección segura
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/';

    // ✅ Validar que la URL sea del mismo dominio
    $parsed = parse_url($redirectUrl);
    $currentHost = $_SERVER['HTTP_HOST'] ?? '';

    if (isset($parsed['host']) && $parsed['host'] !== $currentHost) {
        $redirectUrl = '/';
    }

    header('Location: ' . $redirectUrl);
    exit;

} catch (Exception $e) {
    error_log('AddProduct error: ' . $e->getMessage());
    $_SESSION['ErrorMessage'] = 'Error al procesar la solicitud';
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit;
}
