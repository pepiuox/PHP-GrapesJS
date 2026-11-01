<?php
declare(strict_types=1);

/**
 * SHIM de compatibilidad — NO añadir lógica nueva aquí.
 *
 * La implementación real vive en `PepiuoX\Database\Connection`
 * (`core/src/Database/Connection.php`), con namespace.
 *
 * Este archivo existe para que el código legado siga funcionando sin cambios:
 *
 *     $link = new Database();          // ⇢ PepiuoX\Database\Connection
 *     $conn = $link->getConnection();  // ⇢ PDO compartido
 *
 * Motivo: la clase global `Database` debe ser siempre la conexión canónica. En el
 * proyecto convivían dos clases con nombre parecido y la resolución dependía del orden
 * de carga:
 *
 *   - `Database`                    → conexión canónica (esta, singleton, `getConnection()`)
 *   - `App\Core\Database`           → wrapper con DSN en `core/controller/Database.php`
 *                                     (sin namespace de proyecto y sin uso en la aplicación)
 *
 * Este shim + el `COMPAT_MAP` de `core/Autoload.php` hacen la resolución determinista.
 *
 * @see docs/PDO-MIGRATION.md §10
 */

require_once dirname(__DIR__) . '/src/Database/Connection.php';

// Guard: evita el aviso "Cannot declare class Database, because the name is already in use"
// si este archivo se incluye más de una vez (dbconnection.php histórico usaba `include`).
if (!class_exists('Database', false)) {
    class_alias(\PepiuoX\Database\Connection::class, 'Database');
}
