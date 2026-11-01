<?php
declare(strict_types=1);

/**
 * Redirección del índice del instalador a la raíz del sitio.
 *
 * ✅ CORREGIDO: usaba `$_SERVER['HTTP_HOST']` (cabecera `Host` controlada por el
 * cliente) para construir el destino → inyección de host / open redirect.
 * Con `Location: /` se apunta al mismo sitio sin depender de esa cabecera.
 */

header('Location: /', true, 302);
die();
