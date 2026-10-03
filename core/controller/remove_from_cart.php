<?php
declare(strict_types=1);

/**
 * Eliminación de productos del carrito.
 * Migrado a PDO con validaciones de seguridad.
 *
 * CORRECCIONES:
 * - CSRF protection añadida
 * - Validación de sesión
 * - Validación de producto_id
 * - Redirección segura
 * - Logging de acciones
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

if (empty($_SESSION['client_session'])) {
    $_SESSION['ErrorMessage'] = 'Sesión no válida';
    header('Location: /signin/login');
    exit;
}

// ✅ Validar y sanitizar producto_id
$producto_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($producto_id === false || $producto_id === null || $producto_id <= 0) {
    $_SESSION['ErrorMessage'] = 'ID de producto inválido';
    header('Location: /cart');
    exit;
}

// ✅ Incluir dependencias
$path = defined('URL') ? URL : '';
require_once __DIR__ . '/addCart.php';

try {
    // ✅ Obtener conexión PDO
    $db = new Database();
    $dbprd = $db->PdoConnection('ecommerce');

    // ✅ Inicializar objeto del carrito
    $cart_item = new addCart($dbprd);
    $cart_item->session_key = $_SESSION['client_session'];
    $cart_item->producto_id = $producto_id;

    // ✅ Verificar que el producto existe en el carrito del usuario
    if (!$cart_item->existsInCart()) {
        $_SESSION['ErrorMessage'] = 'El producto no está en tu carrito';
        header('Location: /cart');
        exit;
    }

    // ✅ Eliminar del carrito
    $deleted = $cart_item->deleteSession();

    if ($deleted) {
        $_SESSION['SuccessMessage'] = 'Producto eliminado del carrito';

        // ✅ Log de la acción
        error_log("Cart: producto {$producto_id} eliminado de sesión {$_SESSION['client_session']}");
    } else {
        $_SESSION['ErrorMessage'] = 'Error al eliminar el producto';
    }

    // ✅ Redirección segura
    $redirectUrl = '/cart?action=removed&id=' . $producto_id;
    header('Location: ' . $redirectUrl);
    exit;

} catch (Exception $e) {
    error_log('remove_from_cart error: ' . $e->getMessage());
    $_SESSION['ErrorMessage'] = 'Error al procesar la solicitud';
    header('Location: /cart');
    exit;
}
