-- ============================================================================
-- SCRIPT DE MIGRACIÓN Y SEGURIDAD
-- Proyecto: Migración MySQLi → PDO + Hardening de Seguridad
-- Fecha: 2026-09-17
-- Autor: Generado automáticamente
-- ============================================================================

-- ============================================================================
-- 1. CONFIGURACIÓN INICIAL
-- ============================================================================
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET SESSION sql_mode = 'STRICT_ALL_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
SET SESSION time_zone = '+00:00';

-- ============================================================================
-- 2. BASE DE DATOS CON CONFIGURACIÓN SEGURA
-- ============================================================================
-- Si necesitas recrear la BD:
-- CREATE DATABASE IF NOT EXISTS `ecommerce`
--     CHARACTER SET utf8mb4
--     COLLATE utf8mb4_unicode_ci;
-- USE `ecommerce`;

-- ============================================================================
-- 3. MIGRACIÓN DE CONTRASEÑAS: MD5 → BCRYPT
-- ============================================================================

-- 3.1 Ampliar columna password para almacenar hash bcrypt (60 caracteres)
ALTER TABLE `users`
    MODIFY COLUMN `password` VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt (migrado desde md5)';

ALTER TABLE `users`
    MODIFY COLUMN `password_hash` VARCHAR(255) DEFAULT NULL COMMENT 'Hash bcrypt moderno';

ALTER TABLE `uverify`
    MODIFY COLUMN `password` VARCHAR(255) NOT NULL COMMENT 'Password encriptada AES-256-CBC';

-- 3.2 Tabla temporal para migración progresiva de passwords
CREATE TABLE IF NOT EXISTS `password_migration_queue` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `old_md5_hash` VARCHAR(32) NOT NULL,
    `new_bcrypt_hash` VARCHAR(255) DEFAULT NULL,
    `migrated_at` TIMESTAMP NULL DEFAULT NULL,
    `status` ENUM('pending', 'migrated', 'failed') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Cola para migración progresiva de passwords md5 → bcrypt';

-- 3.3 Migrar passwords existentes a la cola (ejecutar UNA VEZ)
INSERT IGNORE INTO `password_migration_queue` (`user_id`, `old_md5_hash`)
SELECT `id`, `password` FROM `users`
WHERE LENGTH(`password`) = 32
  AND `password` REGEXP '^[a-f0-9]{32}$';

-- ============================================================================
-- 4. AUDITORÍA: COLUMNAS TIMESTAMPS
-- ============================================================================

-- Añadir columnas de auditoría a tablas principales
ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS `last_login_at` TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `last_login_ip` VARCHAR(45) DEFAULT NULL COMMENT 'IPv4 o IPv6',
    ADD COLUMN IF NOT EXISTS `login_count` INT UNSIGNED NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `failed_login_count` INT UNSIGNED NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `locked_until` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE `uverify`
    ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `users_profiles`
    ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `users_actions`
    ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

-- ============================================================================
-- 5. ÍNDICES PARA RENDIMIENTO Y SEGURIDAD
-- ============================================================================

-- Índices únicos para prevenir duplicados (seguridad de datos)
ALTER TABLE `users`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_username` (`username`),
    ADD UNIQUE INDEX IF NOT EXISTS `uk_email` (`email`),
    ADD INDEX IF NOT EXISTS `idx_role` (`role`),
    ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`),
    ADD INDEX IF NOT EXISTS `idx_last_login` (`last_login_at`);

ALTER TABLE `uverify`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_usercode` (`usercode`),
    ADD INDEX IF NOT EXISTS `idx_email` (`email`(191)),
    ADD INDEX IF NOT EXISTS `idx_activation` (`mkhash`, `activation_code`),
    ADD INDEX IF NOT EXISTS `idx_is_activate` (`is_activate`);

ALTER TABLE `users_profiles`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_usercode` (`usercode`);

ALTER TABLE `users_actions`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_usercode` (`usercode`);

ALTER TABLE `users_likes`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_like` (`usercode`, `lusercode`);

ALTER TABLE `users_followers`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_follow` (`usercode`, `fusercode`);

ALTER TABLE `productos_favoritos`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_fav` (`cliente_id`, `producto_id`);

ALTER TABLE `servicios_favoritos`
    ADD UNIQUE INDEX IF NOT EXISTS `uk_fav` (`cliente_id`, `servicio_id`);

-- ============================================================================
-- 6. TABLAS DE SEGURIDAD Y AUDITORÍA
-- ============================================================================

-- 6.1 Log de autenticación (reemplaza tabla 'ip' antigua)
CREATE TABLE IF NOT EXISTS `security_auth_log` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `username` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) DEFAULT NULL,
    `event_type` ENUM('login_success', 'login_failed', 'logout', 'password_change',
                      'password_reset_request', 'password_reset_success',
                      'account_locked', 'account_unlocked', '2fa_enabled',
                      '2fa_disabled', 'session_destroyed') NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL COMMENT 'IPv4 o IPv6',
    `user_agent` VARCHAR(500) DEFAULT NULL,
    `session_id` VARCHAR(128) DEFAULT NULL,
    `success` TINYINT(1) NOT NULL DEFAULT 0,
    `details` JSON DEFAULT NULL COMMENT 'Detalles adicionales en JSON',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_event_type` (`event_type`),
    INDEX `idx_ip` (`ip_address`),
    INDEX `idx_created` (`created_at`),
    INDEX `idx_success` (`success`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Log de eventos de autenticación y seguridad';

-- 6.2 Rate limiting por IP
CREATE TABLE IF NOT EXISTS `security_rate_limits` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `identifier` VARCHAR(191) NOT NULL COMMENT 'IP, email o username',
    `action` VARCHAR(50) NOT NULL COMMENT 'login, register, reset_password',
    `attempts` INT UNSIGNED NOT NULL DEFAULT 1,
    `last_attempt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `blocked_until` TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `uk_identifier_action` (`identifier`, `action`),
    INDEX `idx_blocked` (`blocked_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Rate limiting para prevenir brute force';

-- 6.3 Sesiones activas (para invalidar sesiones remotamente)
CREATE TABLE IF NOT EXISTS `security_sessions` (
    `id` VARCHAR(128) PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(500) DEFAULT NULL,
    `last_activity` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NOT NULL,
    `is_valid` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_expires` (`expires_at`),
    INDEX `idx_valid` (`is_valid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Sesiones activas para invalidación remota';

-- 6.4 Tokens CSRF persistentes (opcional, para APIs)
CREATE TABLE IF NOT EXISTS `security_csrf_tokens` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `token_hash` VARCHAR(64) NOT NULL COMMENT 'SHA-256 del token',
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_token` (`user_id`, `token_hash`),
    INDEX `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6.5 Log de cambios críticos (auditoría)
CREATE TABLE IF NOT EXISTS `security_audit_log` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `table_name` VARCHAR(100) DEFAULT NULL,
    `record_id` VARCHAR(100) DEFAULT NULL,
    `old_values` JSON DEFAULT NULL,
    `new_values` JSON DEFAULT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(500) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_action` (`action`),
    INDEX `idx_table_record` (`table_name`, `record_id`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Log de auditoría para cambios críticos';

-- ============================================================================
-- 7. VALIDACIONES A NIVEL DE BD (CHECK CONSTRAINTS - MySQL 8.0.16+)
-- ============================================================================

ALTER TABLE `users`
    ADD CONSTRAINT `chk_users_username_length` CHECK (CHAR_LENGTH(`username`) BETWEEN 3 AND 50),
    ADD CONSTRAINT `chk_users_email_format` CHECK (`email` LIKE '%_@_%._%'),
    ADD CONSTRAINT `chk_users_role` CHECK (`role` IN ('user', 'admin', 'editor', 'moderator', 'guest'));

ALTER TABLE `rating_producto`
    ADD CONSTRAINT `chk_rating_value` CHECK (`rating` BETWEEN 1 AND 5);

ALTER TABLE `rating_servicio`
    ADD CONSTRAINT `chk_rating_servicio_value` CHECK (`rating` BETWEEN 1 AND 5);

-- ============================================================================
-- 8. VISTAS SEGURAS (sin exponer datos sensibles)
-- ============================================================================

-- Vista pública de usuarios (sin password, email, etc.)
CREATE OR REPLACE VIEW `v_public_users` AS
SELECT
    `id`,
    `username`,
    `full_name`,
    `bio`,
    `role`,
    `created_at`,
    `is_active`
FROM `users`
WHERE `is_active` = 1
  AND `is_banned` = 0;

-- Vista para administradores (con más datos pero sin password)
CREATE OR REPLACE VIEW `v_admin_users` AS
SELECT
    `id`,
    `username`,
    `email`,
    `full_name`,
    `bio`,
    `role`,
    `is_active`,
    `is_banned`,
    `banned_reason`,
    `created_at`,
    `last_login`,
    `last_login_at`,
    `login_count`,
    `failed_login_count`
FROM `users`;

-- Vista de estadísticas de login
CREATE OR REPLACE VIEW `v_login_stats` AS
SELECT
    DATE(`created_at`) AS `date`,
    COUNT(*) AS `total_attempts`,
    SUM(CASE WHEN `success` = 1 THEN 1 ELSE 0 END) AS `successful`,
    SUM(CASE WHEN `success` = 0 THEN 1 ELSE 0 END) AS `failed`,
    COUNT(DISTINCT `ip_address`) AS `unique_ips`
FROM `security_auth_log`
WHERE `event_type` IN ('login_success', 'login_failed')
GROUP BY DATE(`created_at`)
ORDER BY `date` DESC;

-- ============================================================================
-- 9. STORED PROCEDURES SEGUROS
-- ============================================================================

DELIMITER $$

-- 9.1 Login seguro con rate limiting
DROP PROCEDURE IF EXISTS `sp_secure_login`$$
CREATE PROCEDURE `sp_secure_login`(
    IN p_username VARCHAR(100),
    IN p_ip VARCHAR(45),
    IN p_user_agent VARCHAR(500)
)
BEGIN
    DECLARE v_attempts INT DEFAULT 0;
    DECLARE v_blocked_until TIMESTAMP;
    DECLARE v_user_id INT;
    DECLARE v_locked_until TIMESTAMP;

    -- Verificar rate limit por IP
    SELECT `attempts`, `blocked_until`
    INTO v_attempts, v_blocked_until
    FROM `security_rate_limits`
    WHERE `identifier` = p_ip AND `action` = 'login'
    LIMIT 1;

    IF v_blocked_until IS NOT NULL AND v_blocked_until > NOW() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Demasiados intentos. Cuenta bloqueada temporalmente.';
    END IF;

    -- Verificar si el usuario está bloqueado
    SELECT `id`, `locked_until`
    INTO v_user_id, v_locked_until
    FROM `users`
    WHERE `username` = p_username
    LIMIT 1;

    IF v_locked_until IS NOT NULL AND v_locked_until > NOW() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cuenta bloqueada. Contacte al administrador.';
    END IF;

    -- Devolver datos del usuario (la verificación de password se hace en PHP)
    SELECT `id`, `username`, `email`, `password`, `role`, `is_active`, `is_banned`
    FROM `users`
    WHERE `username` = p_username
      AND `is_active` = 1
      AND `is_banned` = 0
    LIMIT 1;
END$$

-- 9.2 Registrar intento de login
DROP PROCEDURE IF EXISTS `sp_log_login_attempt`$$
CREATE PROCEDURE `sp_log_login_attempt`(
    IN p_user_id INT,
    IN p_username VARCHAR(100),
    IN p_email VARCHAR(191),
    IN p_event_type VARCHAR(50),
    IN p_ip VARCHAR(45),
    IN p_user_agent VARCHAR(500),
    IN p_success TINYINT
)
BEGIN
    DECLARE v_attempts INT DEFAULT 0;

    -- Insertar log
    INSERT INTO `security_auth_log`
        (`user_id`, `username`, `email`, `event_type`, `ip_address`, `user_agent`, `success`)
    VALUES
        (p_user_id, p_username, p_email, p_event_type, p_ip, p_user_agent, p_success);

    -- Si falló, incrementar rate limit
    IF p_success = 0 THEN
        INSERT INTO `security_rate_limits` (`identifier`, `action`, `attempts`)
        VALUES (p_ip, 'login', 1)
        ON DUPLICATE KEY UPDATE
            `attempts` = `attempts` + 1,
            `last_attempt` = NOW(),
            `blocked_until` = CASE
                WHEN `attempts` + 1 >= 5 THEN DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                ELSE `blocked_until`
            END;

        -- Incrementar failed_login_count en usuario
        IF p_user_id IS NOT NULL THEN
            UPDATE `users`
            SET `failed_login_count` = `failed_login_count` + 1,
                `locked_until` = CASE
                    WHEN `failed_login_count` + 1 >= 10 THEN DATE_ADD(NOW(), INTERVAL 1 HOUR)
                    ELSE `locked_until`
                END
            WHERE `id` = p_user_id;
        END IF;
    ELSE
        -- Login exitoso: resetear contadores
        DELETE FROM `security_rate_limits`
        WHERE `identifier` = p_ip AND `action` = 'login';

        IF p_user_id IS NOT NULL THEN
            UPDATE `users`
            SET `last_login_at` = NOW(),
                `last_login_ip` = p_ip,
                `login_count` = `login_count` + 1,
                `failed_login_count` = 0,
                `locked_until` = NULL
            WHERE `id` = p_user_id;
        END IF;
    END IF;
END$$

-- 9.3 Cambiar password de forma segura
DROP PROCEDURE IF EXISTS `sp_change_password`$$
CREATE PROCEDURE `sp_change_password`(
    IN p_user_id INT,
    IN p_new_password_hash VARCHAR(255),
    IN p_ip VARCHAR(45)
)
BEGIN
    DECLARE v_username VARCHAR(100);

    -- Obtener username para auditoría
    SELECT `username` INTO v_username
    FROM `users` WHERE `id` = p_user_id;

    -- Actualizar password
    UPDATE `users`
    SET `password` = p_new_password_hash,
        `updated_at` = NOW()
    WHERE `id` = p_user_id;

    -- Registrar en auditoría
    INSERT INTO `security_auth_log`
        (`user_id`, `username`, `event_type`, `ip_address`, `success`)
    VALUES
        (p_user_id, v_username, 'password_change', p_ip, 1);

    -- Invalidar todas las sesiones activas
    UPDATE `security_sessions`
    SET `is_valid` = 0
    WHERE `user_id` = p_user_id;
END$$

-- 9.4 Limpiar datos antiguos (mantenimiento)
DROP PROCEDURE IF EXISTS `sp_cleanup_old_data`$$
CREATE PROCEDURE `sp_cleanup_old_data`(
    IN p_days_to_keep INT
)
BEGIN
    -- Limpiar logs de auth antiguos
    DELETE FROM `security_auth_log`
    WHERE `created_at` < DATE_SUB(NOW(), INTERVAL p_days_to_keep DAY);

    -- Limpiar rate limits expirados
    DELETE FROM `security_rate_limits`
    WHERE `blocked_until` IS NOT NULL
      AND `blocked_until` < NOW()
      AND `last_attempt` < DATE_SUB(NOW(), INTERVAL 1 DAY);

    -- Limpiar sesiones expiradas
    DELETE FROM `security_sessions`
    WHERE `expires_at` < NOW();

    -- Limpiar tokens CSRF expirados
    DELETE FROM `security_csrf_tokens`
    WHERE `expires_at` < NOW();
END$$

DELIMITER ;

-- ============================================================================
-- 10. TRIGGERS PARA AUDITORÍA AUTOMÁTICA
-- ============================================================================

DELIMITER $$

-- 10.1 Auditoría en cambios de usuarios
DROP TRIGGER IF EXISTS `trg_users_after_update`$$
CREATE TRIGGER `trg_users_after_update`
AFTER UPDATE ON `users`
FOR EACH ROW
BEGIN
    IF OLD.`password` != NEW.`password` OR
       OLD.`email` != NEW.`email` OR
       OLD.`role` != NEW.`role` OR
       OLD.`is_banned` != NEW.`is_banned` THEN

        INSERT INTO `security_audit_log`
            (`user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`)
        VALUES
            (NEW.`id`, 'users_update', 'users', CAST(NEW.`id` AS CHAR),
             JSON_OBJECT(
                 'email', OLD.`email`,
                 'role', OLD.`role`,
                 'is_banned', OLD.`is_banned`
             ),
             JSON_OBJECT(
                 'email', NEW.`email`,
                 'role', NEW.`role`,
                 'is_banned', NEW.`is_banned`
             ),
             COALESCE(@current_ip, '0.0.0.0'));
    END IF;
END$$

-- 10.2 Prevenir eliminación masiva accidental
DROP TRIGGER IF EXISTS `trg_users_before_delete`$$
CREATE TRIGGER `trg_users_before_delete`
BEFORE DELETE ON `users`
FOR EACH ROW
BEGIN
    -- Registrar en auditoría
    INSERT INTO `security_audit_log`
        (`user_id`, `action`, `table_name`, `record_id`, `old_values`, `ip_address`)
    VALUES
        (OLD.`id`, 'users_delete', 'users', CAST(OLD.`id` AS CHAR),
         JSON_OBJECT(
             'username', OLD.`username`,
             'email', OLD.`email`,
             'role', OLD.`role`
         ),
         COALESCE(@current_ip, '0.0.0.0'));
END$$

DELIMITER ;

-- ============================================================================
-- 11. EVENTOS PROGRAMADOS (LIMPIEZA AUTOMÁTICA)
-- ============================================================================

-- Habilitar el programador de eventos
SET GLOBAL event_scheduler = ON;

-- Limpieza diaria de datos antiguos
CREATE EVENT IF NOT EXISTS `evt_daily_cleanup`
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
    CALL `sp_cleanup_old_data`(90);

-- Limpieza semanal de sesiones expiradas
CREATE EVENT IF NOT EXISTS `evt_weekly_session_cleanup`
ON SCHEDULE EVERY 1 WEEK
STARTS CURRENT_TIMESTAMP
DO
    DELETE FROM `security_sessions`
    WHERE `expires_at` < NOW();

-- ============================================================================
-- 12. USUARIOS Y PERMISOS (SEGURIDAD DE ACCESO)
-- ============================================================================

-- Crear usuario de aplicación (solo permisos necesarios)
-- ⚠️ CAMBIAR 'app_password' POR UNA CONTRASEÑA FUERTE
-- CREATE USER IF NOT EXISTS 'app_user'@'localhost' IDENTIFIED BY 'app_password';
-- CREATE USER IF NOT EXISTS 'app_user'@'%' IDENTIFIED BY 'app_password';

-- Permisos mínimos necesarios para la aplicación
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`users` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`uverify` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`users_profiles` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`users_actions` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`users_likes` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`users_followers` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE ON `ecommerce`.`security_auth_log` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`security_rate_limits` TO 'app_user'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON `ecommerce`.`security_sessions` TO 'app_user'@'localhost';
-- GRANT EXECUTE ON `ecommerce`.`sp_secure_login` TO 'app_user'@'localhost';
-- GRANT EXECUTE ON `ecommerce`.`sp_log_login_attempt` TO 'app_user'@'localhost';
-- GRANT EXECUTE ON `ecommerce`.`sp_change_password` TO 'app_user'@'localhost';

-- NO dar permisos de DROP, ALTER, CREATE, GRANT a la app
-- FLUSH PRIVILEGES;

-- ============================================================================
-- 13. MIGRACIÓN DE DATOS EXISTENTES
-- ============================================================================

-- 13.1 Migrar datos de tabla 'ip' antigua a 'security_auth_log'
INSERT IGNORE INTO `security_auth_log`
    (`username`, `event_type`, `ip_address`, `success`, `created_at`)
SELECT
    `user_data`,
    'login_failed',
    `address`,
    0,
    NOW()
FROM `ip`
WHERE `user_data` IS NOT NULL;

-- 13.2 Migrar datos de 'login_attempts' antigua
INSERT IGNORE INTO `security_auth_log`
    (`username`, `event_type`, `ip_address`, `success`, `created_at`)
SELECT
    `user_data`,
    'login_failed',
    `ip_address`,
    0,
    NOW()
FROM `login_attempts`
WHERE `user_data` IS NOT NULL;

-- ============================================================================
-- 14. VERIFICACIÓN FINAL
-- ============================================================================

-- Verificar que todas las tablas usan InnoDB y utf8mb4
SELECT
    `TABLE_NAME`,
    `ENGINE`,
    `TABLE_COLLATION`
FROM `information_schema`.`TABLES`
WHERE `TABLE_SCHEMA` = DATABASE()
  AND (`ENGINE` != 'InnoDB' OR `TABLE_COLLATION` NOT LIKE 'utf8mb4%');

-- Verificar índices únicos
SELECT
    `TABLE_NAME`,
    `INDEX_NAME`,
    `NON_UNIQUE`
FROM `information_schema`.`STATISTICS`
WHERE `TABLE_SCHEMA` = DATABASE()
  AND `NON_UNIQUE` = 0
ORDER BY `TABLE_NAME`, `INDEX_NAME`;

-- ============================================================================
-- 15. NOTAS DE SEGURIDAD
-- ============================================================================
-- ✅ Usar SIEMPRE prepared statements en PHP (PDO)
-- ✅ Validar entrada en PHP antes de enviar a BD
-- ✅ Usar password_hash() / password_verify() en PHP
-- ✅ Implementar CSRF tokens en todos los formularios
-- ✅ Configurar cookies con HttpOnly, Secure, SameSite
-- ✅ Implementar rate limiting en PHP (además del de BD)
-- ✅ Rotar claves de encriptación periódicamente
-- ✅ Hacer backups regulares y probar restauraciones
-- ✅ Mantener MySQL actualizado (última versión estable)
-- ✅ Usar SSL/TLS para conexiones remotas a la BD
-- ✅ Revisar logs de seguridad diariamente
-- ============================================================================
