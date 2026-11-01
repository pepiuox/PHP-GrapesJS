<?php
declare(strict_types=1);

/**
 * Redirección del índice de `core/` a la raíz del sitio.
 *
 * ✅ CORREGIDO: antes se construía la URL con `$_SERVER['HTTP_HOST']`:
 *
 *     $site = "http://{$_SERVER['HTTP_HOST']}/";
 *     header("Location: $site");
 *
 * `HTTP_HOST` lo controla el cliente (cabecera `Host`), por lo que permitía
 * **inyección de host / open redirect**: una petición con
 * `Host: evil.example` devolvía `Location: http://evil.example/`.
 * La redirección relativa apunta al mismo sitio sin depender de la cabecera.
 */

header('Location: /', true, 302);
die();
