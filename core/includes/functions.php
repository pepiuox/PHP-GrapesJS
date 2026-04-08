<?php
// includes/functions.php

/**
 * Conjunto de funciones útiles para la aplicación de administración de usuarios
 */
function PathUrl(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

/**
 * Redirige a una URL específica
 * @param string $url URL de destino
 * @param int $status_code Código de estado HTTP (opcional)
 */
function redirect($url, $status_code = 302) {
    header("Location: $url", true, $status_code);
    exit();
}

/**
 * Sanitiza una cadena para prevenir XSS
 * @param string $input Cadena a sanitizar
 * @return string Cadena sanitizada
 */
function sanitize_input($input) {
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Genera un token CSRF
 * @return string Token generado
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida un token CSRF
 * @param string $token Token a validar
 * @return bool True si es válido, false en caso contrario
 */
function validate_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

/**
 * Formatea una fecha para mostrarla de manera amigable
 * @param string $date Fecha en formato válido
 * @param string $format Formato de salida (opcional)
 * @return string Fecha formateada
 */
function format_date($date, $format = 'd/m/Y H:i') {
    if (empty($date) || $date == '0000-00-00 00:00:00') {
        return 'N/A';
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return $date;
    }

    return date($format, $timestamp);
}

/**
 * Obtiene el tiempo transcurrido de manera amigable
 * @param string $date Fecha en formato válido
 * @return string Tiempo transcurrido
 */
function time_elapsed_string($date) {
    if (empty($date)) {
        return 'Nunca';
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return $date;
    }

    $time_diff = time() - $timestamp;

    if ($time_diff < 60) {
        return 'hace unos segundos';
    } elseif ($time_diff < 3600) {
        $minutes = floor($time_diff / 60);
        return "hace {$minutes} minuto" . ($minutes > 1 ? 's' : '');
    } elseif ($time_diff < 86400) {
        $hours = floor($time_diff / 3600);
        return "hace {$hours} hora" . ($hours > 1 ? 's' : '');
    } elseif ($time_diff < 2592000) {
        $days = floor($time_diff / 86400);
        return "hace {$days} día" . ($days > 1 ? 's' : '');
    } elseif ($time_diff < 31536000) {
        $months = floor($time_diff / 2592000);
        return "hace {$months} mes" . ($months > 1 ? 'es' : '');
    } else {
        $years = floor($time_diff / 31536000);
        return "hace {$years} año" . ($years > 1 ? 's' : '');
    }
}

/**
 * Genera un password aleatorio
 * @param int $length Longitud del password
 * @return string Password generado
 */
function generate_random_password($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    $chars_length = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $chars_length)];
    }

    return $password;
}

/**
 * Valida un email
 * @param string $email Email a validar
 * @return bool True si es válido, false en caso contrario
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida un nombre de usuario
 * @param string $username Nombre de usuario a validar
 * @return bool|string True si es válido, mensaje de error en caso contrario
 */
function validate_username($username) {
    if (strlen($username) < 3) {
        return 'El nombre de usuario debe tener al menos 3 caracteres';
    }

    if (strlen($username) > 50) {
        return 'El nombre de usuario no puede exceder los 50 caracteres';
    }

    if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
        return 'El nombre de usuario solo puede contener letras, números, puntos, guiones y guiones bajos';
    }

    return true;
}

/**
 * Valida una contraseña
 * @param string $password Contraseña a validar
 * @return bool|string True si es válida, mensaje de error en caso contrario
 */
function validate_password($password) {
    if (strlen($password) < 6) {
        return 'La contraseña debe tener al menos 6 caracteres';
    }

    if (strlen($password) > 255) {
        return 'La contraseña no puede exceder los 255 caracteres';
    }

    return true;
}

/**
 * Obtiene el badge CSS según el rol del usuario
 * @param string $role Rol del usuario
 * @return string Clase CSS para el badge
 */
function get_role_badge_class($role) {
    $classes = [
        'admin' => 'bg-danger',
        'manager' => 'bg-warning',
        'editor' => 'bg-info',
        'guest' => 'bg-secondary'
    ];

    return $classes[$role] ?? 'bg-secondary';
}

/**
 * Obtiene el texto legible del rol
 * @param string $role Rol del usuario
 * @return string Texto en español del rol
 */
function get_role_text($role) {
    $roles = [
        'admin' => 'Administrador',
        'manager' => 'Manager',
        'editor' => 'Editor',
        'guest' => 'Invitado'
    ];

    return $roles[$role] ?? 'Desconocido';
}

/**
 * Obtiene el icono según el rol del usuario
 * @param string $role Rol del usuario
 * @return string Clase del icono Font Awesome
 */
function get_role_icon($role) {
    $icons = [
        'admin' => 'fas fa-user-shield',
        'manager' => 'fas fa-user-tie',
        'editor' => 'fas fa-user-edit',
        'guest' => 'fas fa-user'
    ];

    return $icons[$role] ?? 'fas fa-user';
}

/**
 * Obtiene el badge CSS según el estado del usuario
 * @param bool $is_active Estado activo
 * @param bool $is_banned Estado baneado
 * @return string Clase CSS para el badge
 */
function get_status_badge_class($is_active, $is_banned) {
    if ($is_banned) {
        return 'bg-danger';
    } elseif ($is_active) {
        return 'bg-success';
    } else {
        return 'bg-secondary';
    }
}

/**
 * Obtiene el texto legible del estado
 * @param bool $is_active Estado activo
 * @param bool $is_banned Estado baneado
 * @return string Texto en español del estado
 */
function get_status_text($is_active, $is_banned) {
    if ($is_banned) {
        return 'Suspendido';
    } elseif ($is_active) {
        return 'Activo';
    } else {
        return 'Inactivo';
    }
}

/**
 * Obtiene el icono según el estado del usuario
 * @param bool $is_active Estado activo
 * @param bool $is_banned Estado baneado
 * @return string Clase del icono Font Awesome
 */
function get_status_icon($is_active, $is_banned) {
    if ($is_banned) {
        return 'fas fa-ban';
    } elseif ($is_active) {
        return 'fas fa-check-circle';
    } else {
        return 'fas fa-times-circle';
    }
}

/**
 * Registra un mensaje flash en la sesión
 * @param string $type Tipo de mensaje (success, error, warning, info)
 * @param string $message Mensaje a mostrar
 */
function set_flash_message($type, $message) {
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message,
        'timestamp' => time()
    ];
}

/**
 * Muestra y limpia los mensajes flash de la sesión
 * @return string HTML con los mensajes flash
 */
function display_flash_messages() {
    if (!isset($_SESSION['flash_messages']) || empty($_SESSION['flash_messages'])) {
        return '';
    }

    $html = '';
    foreach ($_SESSION['flash_messages'] as $flash) {
        $alert_class = 'alert-' . $flash['type'];
        $icon = get_flash_icon($flash['type']);

        $html .= <<<HTML
        <div class="alert $alert_class alert-dismissible fade show" role="alert">
            <i class="$icon me-2"></i>
            {$flash['message']}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
HTML;
    }

    // Limpiar mensajes después de mostrarlos
    unset($_SESSION['flash_messages']);

    return $html;
}

/**
 * Obtiene el icono para un tipo de mensaje flash
 * @param string $type Tipo de mensaje
 * @return string Clase del icono Font Awesome
 */
function get_flash_icon($type) {
    $icons = [
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle'
    ];

    return $icons[$type] ?? 'fas fa-info-circle';
}

/**
 * Genera un token de recuperación de contraseña
 * @return string Token generado
 */
function generate_recovery_token() {
    return bin2hex(random_bytes(32));
}

/**
 * Genera un token de recordar sesión
 * @return string Token generado
 */
function generate_remember_token() {
    return bin2hex(random_bytes(32));
}

/**
 * Calcula la fecha de expiración para un token
 * @param int $hours Horas de validez
 * @return string Fecha de expiración en formato MySQL
 */
function calculate_expiry_date($hours = 24) {
    return date('Y-m-d H:i:s', strtotime("+{$hours} hours"));
}

/**
 * Comprueba si un token ha expirado
 * @param string $expiry_date Fecha de expiración
 * @return bool True si ha expirado, false en caso contrario
 */
function is_token_expired($expiry_date) {
    if (empty($expiry_date)) {
        return true;
    }

    return strtotime($expiry_date) < time();
}

/**
 * Obtiene estadísticas del sistema
 * @param PDO $conn Conexión a la base de datos
 * @return array Array con las estadísticas
 */
function get_system_stats($conn) {
    $stats = [];

    try {
        // Total de usuarios
        $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
        $stats['total_users'] = $stmt->fetchColumn();

        // Usuarios activos
        $stmt = $conn->query("SELECT COUNT(*) as active FROM users WHERE is_active = 1 AND is_banned = 0");
        $stats['active_users'] = $stmt->fetchColumn();

        // Usuarios suspendidos
        $stmt = $conn->query("SELECT COUNT(*) as banned FROM users WHERE is_banned = 1");
        $stats['banned_users'] = $stmt->fetchColumn();

        // Usuarios por rol
        $stmt = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        $stats['users_by_role'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Registros últimos 7 días
        $stmt = $conn->query("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");
        $stats['recent_registrations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Último login promedio
        $stmt = $conn->query("SELECT AVG(login_count) as avg_logins FROM users");
        $stats['avg_logins'] = round($stmt->fetchColumn(), 1);

        // Usuarios con 2FA habilitado
        $stmt = $conn->query("SELECT COUNT(*) as twofa_users FROM users WHERE two_factor_enabled = 1");
        $stats['twofa_users'] = $stmt->fetchColumn();

        // Usuarios que nunca han iniciado sesión
        $stmt = $conn->query("SELECT COUNT(*) as never_logged_in FROM users WHERE last_login IS NULL");
        $stats['never_logged_in'] = $stmt->fetchColumn();
    } catch (PDOException $e) {
        // En caso de error, devolver array vacío
        error_log("Error obteniendo estadísticas: " . $e->getMessage());
        return [];
    }

    return $stats;
}

/**
 * Formatea bytes a un formato legible
 * @param int $bytes Bytes a formatear
 * @param int $precision Precisión decimal
 * @return string Bytes formateados
 */
function format_bytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Obtiene información del servidor
 * @return array Array con información del servidor
 */
function get_server_info() {
    return [
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
        'server_os' => PHP_OS,
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
}

/**
 * Comprueba si una extensión de PHP está cargada
 * @param string $extension Nombre de la extensión
 * @return bool True si está cargada, false en caso contrario
 */
function is_extension_loaded($extension) {
    return extension_loaded($extension);
}

/**
 * Valida y sanitiza un array de datos
 * @param array $data Array de datos a validar
 * @param array $rules Reglas de validación
 * @return array Array con datos validados y errores
 */
function validate_data($data, $rules) {
    $validated = [];
    $errors = [];

    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? '';

        // Sanitizar valor
        $value = sanitize_input($value);

        // Aplicar reglas
        $field_rules = explode('|', $rule);

        foreach ($field_rules as $field_rule) {
            $rule_parts = explode(':', $field_rule);
            $rule_name = $rule_parts[0];
            $rule_param = $rule_parts[1] ?? null;

            switch ($rule_name) {
                case 'required':
                    if (empty($value)) {
                        $errors[$field] = "El campo {$field} es requerido";
                    }
                    break;

                case 'email':
                    if (!empty($value) && !validate_email($value)) {
                        $errors[$field] = "El email no es válido";
                    }
                    break;

                case 'min':
                    if (!empty($value) && strlen($value) < $rule_param) {
                        $errors[$field] = "El campo {$field} debe tener al menos {$rule_param} caracteres";
                    }
                    break;

                case 'max':
                    if (!empty($value) && strlen($value) > $rule_param) {
                        $errors[$field] = "El campo {$field} no puede exceder los {$rule_param} caracteres";
                    }
                    break;

                case 'numeric':
                    if (!empty($value) && !is_numeric($value)) {
                        $errors[$field] = "El campo {$field} debe ser numérico";
                    }
                    break;

                case 'in':
                    if (!empty($value) && !in_array($value, explode(',', $rule_param))) {
                        $errors[$field] = "El valor del campo {$field} no es válido";
                    }
                    break;
            }
        }

        $validated[$field] = $value;
    }

    return [
        'data' => $validated,
        'errors' => $errors
    ];
}

/**
 * Convierte un array a formato JSON para almacenar en la base de datos
 * @param array $data Array a convertir
 * @return string JSON resultante
 */
function array_to_json($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Convierte un JSON de la base de datos a array
 * @param string $json JSON a convertir
 * @return array Array resultante
 */
function json_to_array($json) {
    if (empty($json)) {
        return [];
    }

    $array = json_decode($json, true);
    return is_array($array) ? $array : [];
}

/**
 * Limpia y optimiza la base de datos
 * @param PDO $conn Conexión a la base de datos
 */
function cleanup_database($conn) {
    try {
        // Eliminar tokens de recuperación expirados (más de 24 horas)
        $stmt = $conn->prepare("
            UPDATE users 
            SET recovery_token = NULL, recovery_expires = NULL 
            WHERE recovery_expires < NOW()
        ");
        $stmt->execute();

        // Eliminar tokens de recordar expirados (más de 30 días)
        $stmt = $conn->prepare("
            UPDATE users 
            SET remember_token = NULL, remember_expires = NULL 
            WHERE remember_expires < NOW()
        ");
        $stmt->execute();

        // Log de limpieza
        error_log("Base de datos limpiada: " . date('Y-m-d H:i:s'));
    } catch (PDOException $e) {
        error_log("Error limpiando base de datos: " . $e->getMessage());
    }
}

/**
 * Registra una acción en el log de auditoría
 * @param PDO $conn Conexión a la base de datos
 * @param int $user_id ID del usuario
 * @param string $action Acción realizada
 * @param string $details Detalles de la acción
 */
function log_audit_action($conn, $user_id, $action, $details = '') {
    try {
        $stmt = $conn->prepare("
            INSERT INTO audit_log (user_id, action, details, ip_address, user_agent, created_at)
            VALUES (:user_id, :action, :details, :ip_address, :user_agent, NOW())
        ");

        $stmt->execute([
            ':user_id' => $user_id,
            ':action' => $action,
            ':details' => $details,
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    } catch (PDOException $e) {
        error_log("Error registrando acción de auditoría: " . $e->getMessage());
    }
}

/**
 * Obtiene la URL base de la aplicación
 * @return string URL base
 */
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script_name = dirname($_SERVER['SCRIPT_NAME']);

    return $protocol . $host . rtrim($script_name, '/');
}

/**
 * Genera un enlace absoluto
 * @param string $path Ruta relativa
 * @return string URL absoluta
 */
function url($path = '') {
    $base_url = get_base_url();
    return $base_url . '/' . ltrim($path, '/');
}

/**
 * Comprueba si la petición es AJAX
 * @return bool True si es AJAX, false en caso contrario
 */
function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Devuelve una respuesta JSON
 * @param array $data Datos a enviar
 * @param int $status_code Código de estado HTTP
 */
function json_response($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

/**
 * Maneja excepciones y errores
 * @param Exception|Error $exception Excepción o error
 * @param bool $log_error Si se debe registrar el error
 */
function handle_exception($exception, $log_error = true) {
    if ($log_error) {
        error_log("Error: " . $exception->getMessage() .
                " in " . $exception->getFile() .
                " on line " . $exception->getLine());
    }

    if (is_ajax_request()) {
        json_response([
            'success' => false,
            'error' => 'Ha ocurrido un error interno del servidor'
                ], 500);
    } else {
        // Mostrar página de error
        include 'includes/header.php';
?>
                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card border-danger">
                                        <div class="card-header bg-danger text-white">
                                            <h4><i class="fas fa-exclamation-triangle"></i> Error del Sistema</h4>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Ha ocurrido un error inesperado</h5>
                                            <p class="card-text">El equipo técnico ha sido notificado. Por favor, intente nuevamente más tarde.</p>
                                            <a href="<?php echo url('dashboard.php'); ?>" class="btn btn-primary">
                                                <i class="fas fa-home"></i> Volver al Inicio
                                            </a>
        <?php if (defined('DEBUG') && DEBUG): ?>
                                                    <hr>
                                                    <div class="alert alert-secondary">
                                                        <h6>Información del Error:</h6>
                                                        <p><strong>Mensaje:</strong> <?php echo htmlspecialchars($exception->getMessage()); ?></p>
                                                        <p><strong>Archivo:</strong> <?php echo $exception->getFile(); ?></p>
                                                        <p><strong>Línea:</strong> <?php echo $exception->getLine(); ?></p>
                                                    </div>
        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        <?php
        include 'includes/footer.php';
        exit();
    }
}

/**
 * Configura el manejador de excepciones global
 */
//function set_exception_handler($exception) {


//    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
//        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
//    });
//}

//set_exception_handler(function ($exception) {
//    handle_exception($exception);
//});

/**
 * Crea la tabla de logs de auditoría si no existe
 * @param PDO $conn Conexión a la base de datos
 */
function create_audit_log_table($conn) {
    $sql = "
        CREATE TABLE IF NOT EXISTS audit_log (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            action VARCHAR(100) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_action (action),
            INDEX idx_created_at (created_at),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";

    try {
        $conn->exec($sql);
    } catch (PDOException $e) {
        error_log("Error creando tabla audit_log: " . $e->getMessage());
    }
}

/**
 * Crea la tabla de configuraciones si no existe
 * @param PDO $conn Conexión a la base de datos
 */
function create_settings_table($conn) {
    $sql = "
        CREATE TABLE IF NOT EXISTS settings (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT,
            setting_type ENUM('string', 'integer', 'boolean', 'array', 'json') DEFAULT 'string',
            description TEXT,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_setting_key (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";

    try {
        $conn->exec($sql);
    } catch (PDOException $e) {
        error_log("Error creando tabla settings: " . $e->getMessage());
    }
}

/**
 * Obtiene una configuración del sistema
 * @param PDO $conn Conexión a la base de datos
 * @param string $key Clave de la configuración
 * @param mixed $default Valor por defecto
 * @return mixed Valor de la configuración
 */
function get_setting($conn, $key, $default = null) {
    try {
        $stmt = $conn->prepare("SELECT setting_value, setting_type FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return $default;
        }

        // Convertir según el tipo
        switch ($row['setting_type']) {
            case 'integer':
                return (int) $row['setting_value'];
            case 'boolean':
                return (bool) $row['setting_value'];
            case 'array':
            case 'json':
                return json_decode($row['setting_value'], true);
            default:
                return $row['setting_value'];
        }
    } catch (PDOException $e) {
        error_log("Error obteniendo configuración: " . $e->getMessage());
        return $default;
    }
}

/**
 * Establece una configuración del sistema
 * @param PDO $conn Conexión a la base de datos
 * @param string $key Clave de la configuración
 * @param mixed $value Valor de la configuración
 * @param string $type Tipo de dato
 * @param string $description Descripción
 * @param bool $is_public Si es pública
 * @return bool True si se guardó correctamente
 */
function set_setting($conn, $key, $value, $type = 'string', $description = '', $is_public = false) {
    try {
        // Preparar valor según el tipo
        switch ($type) {
            case 'integer':
                $value = (int) $value;
                break;
            case 'boolean':
                $value = (bool) $value ? '1' : '0';
                break;
            case 'array':
            case 'json':
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
        }

        $stmt = $conn->prepare("
            INSERT INTO settings (setting_key, setting_value, setting_type, description, is_public) 
            VALUES (:key, :value, :type, :description, :is_public)
            ON DUPLICATE KEY UPDATE 
            setting_value = :value, 
            setting_type = :type,
            description = :description,
            is_public = :is_public,
            updated_at = NOW()
        ");

        return $stmt->execute([
                    ':key' => $key,
                    ':value' => $value,
                    ':type' => $type,
                    ':description' => $description,
                    ':is_public' => $is_public ? 1 : 0
        ]);
    } catch (PDOException $e) {
        error_log("Error guardando configuración: " . $e->getMessage());
        return false;
    }
}

/**
 * Inicializa configuraciones por defecto
 * @param PDO $conn Conexión a la base de datos
 */
function initialize_default_settings($conn) {
    $default_settings = [
        [
            'key' => 'site_name',
            'value' => 'Sistema de Administración de Usuarios',
            'type' => 'string',
            'description' => 'Nombre del sitio web',
            'public' => true
        ],
        [
            'key' => 'site_description',
            'value' => 'Sistema de gestión de usuarios desarrollado en PHP',
            'type' => 'string',
            'description' => 'Descripción del sitio web',
            'public' => true
        ],
        [
            'key' => 'items_per_page',
            'value' => 10,
            'type' => 'integer',
            'description' => 'Número de ítems por página en listados',
            'public' => false
        ],
        [
            'key' => 'allow_registration',
            'value' => false,
            'type' => 'boolean',
            'description' => 'Permitir registro de nuevos usuarios',
            'public' => false
        ],
        [
            'key' => 'require_email_verification',
            'value' => false,
            'type' => 'boolean',
            'description' => 'Requerir verificación de email para nuevos usuarios',
            'public' => false
        ],
        [
            'key' => 'max_login_attempts',
            'value' => 5,
            'type' => 'integer',
            'description' => 'Intentos máximos de login antes de bloquear',
            'public' => false
        ],
        [
            'key' => 'session_timeout',
            'value' => 30,
            'type' => 'integer',
            'description' => 'Tiempo de expiración de sesión en minutos',
            'public' => false
        ],
        [
            'key' => 'maintenance_mode',
            'value' => false,
            'type' => 'boolean',
            'description' => 'Modo mantenimiento del sistema',
            'public' => true
        ],
        [
            'key' => 'default_user_role',
            'value' => 'guest',
            'type' => 'string',
            'description' => 'Rol por defecto para nuevos usuarios',
            'public' => false
        ],
        [
            'key' => 'password_min_length',
            'value' => 8,
            'type' => 'integer',
            'description' => 'Longitud mínima de contraseñas',
            'public' => false
        ],
        [
            'key' => 'password_require_complexity',
            'value' => true,
            'type' => 'boolean',
            'description' => 'Requerir complejidad en contraseñas',
            'public' => false
        ]
    ];

    foreach ($default_settings as $setting) {
        if (get_setting($conn, $setting['key']) === null) {
            set_setting(
                    $conn,
                    $setting['key'],
                    $setting['value'],
                    $setting['type'],
                    $setting['description'],
                    $setting['public']
            );
        }
    }
}

/**
 * Comprueba si el sistema está en modo mantenimiento
 * @param PDO $conn Conexión a la base de datos
 * @return bool True si está en mantenimiento
 */
function is_maintenance_mode($conn) {
    return get_setting($conn, 'maintenance_mode', false);
}

/**
 * Verifica los requisitos del sistema
 * @return array Array con los resultados de la verificación
 */
function check_system_requirements() {
    $requirements = [
        'php_version' => [
            'required' => '7.4.0',
            'current' => PHP_VERSION,
            'met' => version_compare(PHP_VERSION, '7.4.0', '>=')
        ],
        'pdo_mysql' => [
            'required' => true,
            'met' => extension_loaded('pdo_mysql')
        ],
        'mbstring' => [
            'required' => true,
            'met' => extension_loaded('mbstring')
        ],
        'json' => [
            'required' => true,
            'met' => extension_loaded('json')
        ],
        'openssl' => [
            'required' => true,
            'met' => extension_loaded('openssl')
        ],
        'session' => [
            'required' => true,
            'met' => extension_loaded('session')
        ],
        'file_uploads' => [
            'required' => true,
            'met' => ini_get('file_uploads') == '1'
        ],
        'upload_max_filesize' => [
            'required' => '2M',
            'current' => ini_get('upload_max_filesize'),
            'met' => intval(ini_get('upload_max_filesize')) >= 2
        ],
        'post_max_size' => [
            'required' => '8M',
            'current' => ini_get('post_max_size'),
            'met' => intval(ini_get('post_max_size')) >= 8
        ],
        'memory_limit' => [
            'required' => '128M',
            'current' => ini_get('memory_limit'),
            'met' => intval(ini_get('memory_limit')) >= 128
        ]
    ];

    $all_met = true;
    foreach ($requirements as $requirement) {
        if (!$requirement['met']) {
            $all_met = false;
            break;
        }
    }

    return [
        'requirements' => $requirements,
        'all_met' => $all_met
    ];
}

/**
 * Muestra los resultados de la verificación de requisitos
 * @return string HTML con los resultados
 */
function display_requirements_check() {
    $check = check_system_requirements();
    $html = '<div class="card mb-4">';
    $html .= '<div class="card-header"><h5 class="mb-0"><i class="fas fa-server"></i> Verificación de Requisitos del Sistema</h5></div>';
    $html .= '<div class="card-body">';
    $html .= '<div class="table-responsive">';
    $html .= '<table class="table table-hover">';
    $html .= '<thead><tr><th>Requisito</th><th>Requerido</th><th>Actual</th><th>Estado</th></tr></thead>';
    $html .= '<tbody>';

    foreach ($check['requirements'] as $name => $requirement) {
        $status_class = $requirement['met'] ? 'success' : 'danger';
        $status_icon = $requirement['met'] ? 'fa-check-circle' : 'fa-times-circle';
        $status_text = $requirement['met'] ? 'OK' : 'FALLO';

        $html .= '<tr>';
        $html .= '<td>' . ucfirst(str_replace('_', ' ', $name)) . '</td>';
        $html .= '<td>' . (is_bool($requirement['required']) ? ($requirement['required'] ? 'Sí' : 'No') : $requirement['required']) . '</td>';
        $html .= '<td>' . ($requirement['current'] ?? 'N/A') . '</td>';
        $html .= '<td><span class="badge bg-' . $status_class . '"><i class="fas ' . $status_icon . '"></i> ' . $status_text . '</span></td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    if ($check['all_met']) {
        $html .= '<div class="alert alert-success mt-3"><i class="fas fa-check"></i> Todos los requisitos están cumplidos. El sistema puede funcionar correctamente.</div>';
    } else {
        $html .= '<div class="alert alert-danger mt-3"><i class="fas fa-exclamation-triangle"></i> Algunos requisitos no están cumplidos. Por favor, corrija los problemas antes de continuar.</div>';
    }

    $html .= '</div></div>';
    return $html;
}

// Agregar al final del archivo functions.php

/**
 * Enviar email de recuperación de contraseña
 */
function send_password_reset_email($to_email, $to_name, $reset_link) {
    $subject = "Recuperación de Contraseña - Sistema de Usuarios";

    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Recuperación de Contraseña</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                     color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; padding: 12px 30px; background: #4361ee; 
                     color: white; text-decoration: none; border-radius: 5px; 
                     font-weight: bold; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Recuperación de Contraseña</h1>
            </div>
            <div class="content">
                <h2>Hola ' . htmlspecialchars($to_name) . ',</h2>
                <p>Haz clic en el siguiente botón para restablecer tu contraseña:</p>
                <div style="text-align: center;">
                    <a href="' . $reset_link . '" class="button">
                        Restablecer Contraseña
                    </a>
                </div>
                <p>Este enlace expirará en 1 hora.</p>
            </div>
        </div>
    </body>
    </html>';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: Sistema de Usuarios <no-reply@tudominio.com>" . "\r\n";

    // En desarrollo, guardar en log
    $log_file = 'logs/email_log.txt';
    $log_entry = date('Y-m-d H:i:s') . " - To: $to_email - Link: $reset_link\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);

    return true; // mail($to_email, $subject, $message, $headers);
}

/**
 * Obtener estadísticas para gráficos
 */
function get_chart_data($conn, $period = 30) {
    try {
        $query = "
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as total,
                COUNT(CASE WHEN role = 'admin' THEN 1 END) as admins,
                COUNT(CASE WHEN role = 'manager' THEN 1 END) as managers,
                COUNT(CASE WHEN role = 'editor' THEN 1 END) as editors,
                COUNT(CASE WHEN role = 'guest' THEN 1 END) as guests
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute([$period]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error obteniendo datos para gráfico: " . $e->getMessage());
        return [];
    }
}

/**
 * Generar token de recuperación
 */
function send_generate_recovery_token($conn, $user_id) {
    try {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $conn->prepare("
            UPDATE users 
            SET recovery_token = ?, recovery_expires = ? 
            WHERE id = ?
        ");

        if ($stmt->execute([$token, $expires, $user_id])) {
            return $token;
        }

        return false;
    } catch (PDOException $e) {
        error_log("Error generando token: " . $e->getMessage());
        return false;
    }
}

/**
 * Validar token de recuperación
 */
function validate_recovery_token($conn, $token) {
    try {
        $stmt = $conn->prepare("
            SELECT id, email, recovery_expires 
            FROM users 
            WHERE recovery_token = ? 
            AND recovery_expires > NOW()
            AND is_active = 1 
            AND is_banned = 0
        ");

        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error validando token: " . $e->getMessage());
        return false;
    }
}

        ?>