<?php
declare(strict_types=1);

/**
 * SHIM de compatibilidad — NO añadir lógica aquí.
 *
 * Antes declaraba otra clase global `Autoload` (la quinta del proyecto).
 * Ver la nota completa en `core/pages/Autoload.php`.
 *
 * El autoloader único del sistema es `core/Autoload.php`.
 *
 * Nota: este archivo lo requiere `core/application/installer/install.php`
 * con `require_once "Autoload.php"`.
 *
 * @see docs/PDO-MIGRATION.md §10
 */

require_once dirname(__DIR__, 2) . '/Autoload.php';
