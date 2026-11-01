<?php
declare(strict_types=1);

/**
 * SHIM de compatibilidad — NO añadir lógica aquí.
 *
 * Antes declaraba otra clase global `Autoload` que apuntaba a `../classes/`
 * (directorio inexistente). Ver la nota completa en `core/pages/Autoload.php`.
 *
 * El autoloader único del sistema es `core/Autoload.php`.
 *
 * @see docs/PDO-MIGRATION.md §10
 */

require_once dirname(__DIR__) . '/Autoload.php';
