<?php
declare(strict_types=1);

/**
 * SHIM de compatibilidad — NO añadir lógica aquí.
 *
 * Antes este archivo declaraba su propia clase global `Autoload` y registraba un
 * autoloader que apuntaba a `../../core/classes/` (directorio inexistente) y lanzaba
 * una excepción por cualquier clase ajena al proyecto (p. ej. PHPMailer).
 *
 * Además, había CINCO archivos declarando `class Autoload` (raíz, core/, core/managers/,
 * core/pages/, core/users/, installer/): cargar dos cualesquiera provocaba
 * "Cannot declare class Autoload, because the name is already in use".
 *
 * Ahora hay un único autoloader: `core/Autoload.php` (`PepiuoX\Autoloader\ClassLoader`).
 *
 * @see docs/PDO-MIGRATION.md §10
 */

require_once dirname(__DIR__) . '/Autoload.php';
