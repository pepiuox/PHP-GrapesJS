# 01 — Metodología y alcance

## Objetivo

Verificar qué funcionalidades del CMS funcionan de verdad, documentar el proceso y corregir
lo que impide su uso, sin tocar las carpetas de terceros ni el front-end compilado.

## Alcance

Analizado y modificado:

- `core/` — 560 archivos PHP: capa de aplicación (`controller/`, `managers/`, `pages/`,
  `users/`, `includes/`, `elements/`, `templates/`, `blocks/`, `file-manager/`, `application/`) y
  código nuevo con namespace (`core/src/`).
- `build/index.php` y `build/main.php` — puntos de entrada del sitio.
- `.htaccess` de raíz, `build/`, `core/`, `core/config/`, `core/pages/`.

Excluido de análisis y de modificación (decisión del usuario):

- `vendor/` — dependencias de Composer.
- `plugin/` y `build/assets/plugins/` — plugins de terceros.
- `build/assets/`, `build/themes/`, `build/uploads/` — recursos estáticos y subidas.
- `core/PHPMailer/` y `core/filemanager/` (elFinder) — bibliotecas de terceros embebidas: se
  tratan como cajas negras y solo se documentan sus puntos de integración.

## Entorno verificado (hecho observado)

| Elemento | Valor comprobado |
|---|---|
| PHP CLI | 8.4.25 (NTS), extensiones `pdo_mysql`, `gd`, `mbstring`, `openssl`, `curl` |
| Servidor HTTP | Apache 2.4.68 (Win64), PHP 8.4.25 como módulo, `http://localhost:150/` |
| Base de datos | MariaDB **12.3.3**, base `phpgrapesjs`, 115 tablas, 2.538 filas |
| Herramientas del proyecto | PHPStan 2.2.14, PHPUnit 13.3.4, PHPCS 4.0.4, php-cs-fixer 3.95.25 en `vendor/` |

## Reglas de evidencia

- **Hecho observado**: leído en un archivo, ejecutado con PHP/Apache o consultado a MariaDB.
- **Inferencia**: deducción a partir de hechos, señalada como tal.
- **Recomendación**: propuesta con justificación técnica.

Todo hallazgo de este informe tiene una prueba reproducible en `tools/` o `tests/`. Ningún
resultado se declaró sin ejecutarlo; lo que no se pudo ejecutar se indica expresamente.

## Secuencia seguida

1. Inventario del árbol y de los puntos de entrada.
2. Verificación de sintaxis de los 560 archivos PHP (`tools/lint.php`).
3. Auditoría de inyección de dependencias (`tools/DiAudit.php`, `tools/audit-di.php`).
4. Análisis del flujo de acceso, del cifrado y del modelo de datos real
   (`tools/inspect-db.php`).
5. Respaldo lógico de la base antes de tocar nada (`tools/backup-db.php`).
6. Correcciones por parches idempotentes y reversibles (`tools/patch-*.php`), cada una con
   respaldo en `storage/backups/patch-<fecha>/` y verificación de sintaxis posterior.
7. Verificación funcional: unidad (`tests/`), datos (`tools/verify-login.php`) y HTTP real
   (`tools/http-smoke.php`, `tools/verify-login-http.php`).
8. Documentación del proceso en esta carpeta.

## Riesgos del propio análisis

- Ejecutar la prueba de acceso real **inicia sesión de verdad**: el identificador del usuario
  rota (es el comportamiento de diseño) y queda un registro en `ip`. Se hizo una sola vez y se
  documenta en [06](06-PRUEBAS-Y-EVIDENCIAS.md).
- Los intentos fallidos previos a la corrección dejaron filas en `ip`; tras la corrección solo
  cuentan los últimos 10 minutos, así que ya no bloquean la cuenta.