<?php
declare(strict_types=1);

/**
 * Controlador del sistema con validaciones de seguridad.
 *
 * CORRECCIONES:
 * - Validación de permisos (RBAC)
 * - CSRF protection en operaciones POST
 * - Validación de inputs
 * - Logging de acciones críticas
 * - Tipado estricto
 */
class SystemController
{
    private PDO $db;
    private RoleManager $roleManager;
    private ActivityLogger $logger;

    /**
     * ✅ Lista blanca de acciones permitidas
     */
    private const ALLOWED_ACTIONS = [
        'config', 'system_info', 'cache_clear',
        'logs_view', 'backup_create', 'maintenance'
    ];

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->roleManager = new RoleManager($db);
        $this->logger = new ActivityLogger($db);
    }

    /**
     * Muestra configuración del sistema (solo admin).
     *
     * ✅ Validación de permisos
     */
    public function config(): void
    {
        // ✅ Verificar permisos de administrador
        if (!SessionManager::isAdmin()) {
            http_response_code(403);
            echo '<div class="alert alert-danger">Acceso denegado. Se requieren permisos de administrador.</div>';
            return;
        }

        // ✅ CSRF validation para operaciones POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!SessionManager::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
                return;
            }
        }

        try {
            // ✅ Obtener configuración del sistema
            $stmt = $this->db->query(
                'SELECT config_name, config_value, updated_at
                FROM site_configuration
                ORDER BY config_name ASC'
            );
            $config = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // ✅ Obtener información del servidor
            $serverInfo = $this->getServerInfo();

            // ✅ Renderizar (con escape para prevenir XSS)
            echo '<div class="container">';
            echo '<h1>Configuración del Sistema</h1>';

            echo '<div class="card mb-4">';
            echo '<div class="card-header">Información del Servidor</div>';
            echo '<div class="card-body">';
            echo '<table class="table table-sm">';
            foreach ($serverInfo as $key => $value) {
                echo '<tr><th>' . $this->escape($key) . '</th>';
                echo '<td>' . $this->escape((string) $value) . '</td></tr>';
            }
            echo '</table>';
            echo '</div></div>';

            echo '<div class="card">';
            echo '<div class="card-header">Configuración del Sitio</div>';
            echo '<div class="card-body">';
            echo '<table class="table table-sm">';
            echo '<thead><tr><th>Nombre</th><th>Valor</th><th>Actualizado</th></tr></thead>';
            echo '<tbody>';
            foreach ($config as $row) {
                // ✅ Ocultar valores sensibles
                $value = $this->maskSensitiveValue($row['config_name'], $row['config_value']);
                echo '<tr>';
                echo '<td>' . $this->escape($row['config_name']) . '</td>';
                echo '<td><code>' . $this->escape($value) . '</code></td>';
                echo '<td>' . $this->escape($row['updated_at'] ?? '') . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
            echo '</div></div>';
            echo '</div>';

            // ✅ Log de acceso
            $this->logger->logActivity(
                SessionManager::getUserId() ?? 0,
                                       'settings_change',
                                       'Acceso a configuración del sistema'
            );

        } catch (PDOException $e) {
            error_log('SystemController::config error: ' . $e->getMessage());
            echo '<div class="alert alert-danger">Error al cargar la configuración.</div>';
        }
    }

    /**
     * Obtiene información del servidor.
     */
    private function getServerInfo(): array
    {
        $dbInfo = [];
        try {
            $stmt = $this->db->query('SELECT VERSION() as version');
            $dbInfo['MySQL Version'] = $stmt->fetchColumn();

            $stmt = $this->db->query("SHOW VARIABLES LIKE 'character_set_database'");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $dbInfo['Database Charset'] = $row['Value'] ?? 'Unknown';
        } catch (PDOException $e) {
            $dbInfo['Database Error'] = 'No disponible';
        }

        return [
            'PHP Version'       => PHP_VERSION,
            'Server Software'   => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'Document Root'     => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'Server IP'         => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
            'Max Upload Size'   => ini_get('upload_max_filesize'),
            'Max Post Size'     => ini_get('post_max_size'),
            'Memory Limit'      => ini_get('memory_limit'),
            'Max Execution Time'=> ini_get('max_execution_time') . 's',
            'MySQL Version'     => $dbInfo['MySQL Version'] ?? 'Unknown',
            'Database Charset'  => $dbInfo['Database Charset'] ?? 'Unknown',
            'Current Time'      => date('Y-m-d H:i:s'),
            'Timezone'          => date_default_timezone_get(),
        ];
    }

    /**
     * Enmascara valores sensibles.
     */
    private function maskSensitiveValue(string $name, string $value): string
    {
        $sensitive = ['password', 'secret', 'token', 'key', 'api'];
        $nameLower = strtolower($name);

        foreach ($sensitive as $keyword) {
            if (strpos($nameLower, $keyword) !== false) {
                return '********';
            }
        }

        return $value;
    }

    /**
     * Limpia caché del sistema (solo admin).
     *
     * ✅ CSRF + permisos + logging
     */
    public function clearCache(): bool
    {
        if (!SessionManager::isAdmin()) {
            return false;
        }

        if (!SessionManager::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            return false;
        }

        try {
            $cache = new Cache($this->db);
            $result = $cache->clearAllCache();

            if ($result) {
                $this->logger->logActivity(
                    SessionManager::getUserId() ?? 0,
                                           'cache_clear',
                                           'Caché del sistema limpiado'
                );
            }

            return $result;
        } catch (Exception $e) {
            error_log('SystemController::clearCache error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ejecuta acción del sistema.
     *
     * ✅ Validación contra lista blanca
     */
    public function executeAction(string $action): mixed
    {
        if (!SessionManager::isAdmin()) {
            http_response_code(403);
            return null;
        }

        // ✅ Validar acción contra lista blanca
        if (!in_array($action, self::ALLOWED_ACTIONS, true)) {
            http_response_code(400);
            return null;
        }

        return match ($action) {
            'config'        => $this->config(),
            'system_info'   => $this->getServerInfo(),
            'cache_clear'   => $this->clearCache(),
            'logs_view'     => $this->viewLogs(),
            default         => null,
        };
    }

    /**
     * Muestra logs del sistema.
     */
    private function viewLogs(): void
    {
        if (!SessionManager::isAdmin()) {
            http_response_code(403);
            return;
        }

        $limit = max(1, min((int) ($_GET['limit'] ?? 100), 1000));
        $logs = $this->logger->getUserActivities(null, $limit);

        echo '<h1>Logs del Sistema</h1>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Fecha</th><th>Usuario</th><th>Tipo</th><th>Descripción</th><th>IP</th></tr></thead>';
        echo '<tbody>';

        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . $this->escape($log['formatted_date'] ?? '') . '</td>';
            echo '<td>' . $this->escape($log['username'] ?? 'Sistema') . '</td>';
            echo '<td>' . $this->escape($log['activity_type'] ?? '') . '</td>';
            echo '<td>' . $this->escape($log['description'] ?? '') . '</td>';
            echo '<td>' . $this->escape($log['ip_address'] ?? '') . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    /**
     * Escapa strings para output HTML.
     */
    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
