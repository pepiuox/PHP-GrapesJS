# 06 — Instrumentos, pruebas y evidencias

## Instrumentos (`tools/`)

| Instrumento | Qué hace | Efectos |
|---|---|---|
| `lint.php` | `php -l` sobre todo el árbol PHP (excluye terceros y artefactos) | Ninguno |
| `DiAudit.php` + `audit-di.php` | Detecta clases con constructor que exige tipos y se instancian sin argumentos | Ninguno |
| `scan-risk.php` | Localiza patrones de CWE (md5/sha1, eval, include variable, SQL concatenado, salida sin escapar, secretos en código…) y escribe informe | Escribe solo el informe |
| `inspect-db.php` | Inventario de tablas, filas e **identificación del formato de cifrado** de cada columna sensible sin revelar valores | Solo lectura |
| `backup-db.php` | Volcado lógico completo (esquema + datos) en PHP puro, sin cliente externo | Escribe el `.sql` |
| `verify-login.php` | Reproduce la cadena de acceso contra la base dentro de una transacción con `ROLLBACK` final | Ninguno (rollback) |
| `verify-login-http.php` | Acceso real por HTTP con las credenciales de la base, descifradas en memoria | Inicia y cierra sesión real |
| `http-smoke.php` | Comprueba rutas y busca marcadores de error fatal en la respuesta | Ninguno |
| `patch-di.php`, `patch-crypto.php`, `patch-login.php`, `patch-id-casts.php`, `patch-credentials.php` | Parches idempotentes con respaldo y `php -l` posterior | Modifican código, con respaldo |

## Suite de pruebas (`tests/`)

`tests/bootstrap.php` carga el autoloader del proyecto sin abrir base de datos.

| Archivo | Pruebas | Qué fija |
|---|---|---|
| `tests/Unit/CryptoContractTest.php` | 9 | Determinismo, ida y vuelta, lectura de los tres formatos históricos, generadores de token, `procheck` |
| `tests/Unit/SecurityPrimitivesTest.php` | 9 | `SqlIdentifier` (incluidas 8 cargas de inyección), lista blanca, escapes, `Random`, `SessionConfig`, CSRF |
| `tests/Unit/AutoloadTest.php` | 4 | Clases legadas, shim `Database`, PSR-4, ausencia de excepciones |
| `tests/Architecture/DependencyInjectionTest.php` | 3 | Cero instanciaciones incompatibles, `Connection::shared()` presente, constructores sin PDO obligatorio |

## Resultados de la ejecución final (2026-10-17 17:34)

| Verificación | Comando | Resultado |
|---|---|---|
| Sintaxis | `php tools/lint.php` | 558 archivos, **0 errores** |
| Inyección de dependencias | `php tools/audit-di.php` | 127 clases analizadas, **0 incompatibilidades** (antes: 58) |
| Pruebas unitarias/arquitectura | `vendor/phpunit/phpunit/phpunit` | **23 pruebas, 72 aserciones, OK** |
| Cadena de acceso contra la base | `php tools/verify-login.php` | **15/15 PASS** |
| Humo HTTP | `php tools/http-smoke.php` | **8/8 rutas OK** |
| Acceso real | `php tools/verify-login-http.php` | **8/8 PASS** |
| Patrones de riesgo | `php tools/scan-risk.php` | 448 archivos, 846 coincidencias clasificadas en 10 categorías |
| Respaldo de base | `php tools/backup-db.php` | 115 tablas, 2.554 filas |

Salidas guardadas: `tools/reports/audit-di.txt`, `tools/reports/scan-risk.md`,
`storage/backups/backup-20261017-171033.sql`, `storage/backups/backup-20261017-173402.sql`.

## Detalle de la verificación del acceso (base real)

`tools/verify-login.php` comprobó, contra `phpgrapesjs` y con `ROLLBACK` al final:

1. `uverify.email`, `.mkpin` y `.username` descifran con las claves del sitio.
2. Re-cifrar el mismo texto reproduce exactamente el valor almacenado (contrato de igualdad).
3. `SELECT … WHERE email = :e AND mkpin = :p AND is_activate = 1` devuelve 1 fila.
4. `users.password` descifra y coincide con `uverify.password`.
5. El PIN coincide entre ambas tablas.
6. `UPDATE uverify SET iduv = …` afecta 1 fila.
7. `UPDATE users_profiles SET mkhash = … WHERE idp = <id nuevo>` afecta 1 fila.
8. La FK `ON UPDATE CASCADE` propaga el identificador nuevo a `users`.

`tools/verify-login-http.php` comprobó por HTTP: formulario 200, token CSRF presente, envío
aceptado sin error fatal, sesión iniciada (perfil 200), identificador rotado, propagación por FK
y cierre de sesión que invalida el acceso (perfil 302).

## Limitaciones de la verificación

- **PHPStan**: `phpstan.neon` no es válido para PHPStan 2.2 (claves eliminadas y rutas
  inexistentes). El análisis se ejecutó apuntando a `core/src` sin ese archivo. Un análisis
  estático de los 560 archivos heredados (nivel alto) queda pendiente porque requiere limpiar
  antes la configuración y los tipos.
- **Cobertura de código**: no se midió porque el proyecto no tiene `php-code-coverage` instalado
  ni driver de cobertura configurado. No se declara cobertura alguna.
- **Logout y rotación**: cada ejecución de `verify-login-http.php` inicia sesión de verdad, de
  modo que el identificador del usuario cambia (es el comportamiento esperado) y queda una fila
  en `ip`.
- **Navegador real**: no se probó la interfaz en navegador; la verificación visual de las seis
  páginas del CMS queda pendiente (Lighthouse y contraste WCAG no aplican a esta entrega).