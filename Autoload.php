<?php
declare(strict_types=1);

/**
 * SHIM de compatibilidad — NO añadir lógica aquí.
 *
 * Antes declaraba otra clase global `Autoload` con una ruta **relativa al directorio
 * de trabajo** (`core/controller/...`), así que sólo funcionaba si el CWD era la raíz.
 *
 * El autoloader único del sistema es `core/Autoload.php`.
 *
 * @see docs/PDO-MIGRATION.md §10
 */

require_once __DIR__ . '/core/Autoload.php';
