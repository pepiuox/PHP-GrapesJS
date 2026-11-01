# 05 — Correcciones aplicadas

Todo cambio se hizo con parches idempotentes y reversibles en `tools/patch-*.php`, cada uno con
respaldo de los originales en `storage/backups/patch-<fecha-hora>/` y verificación `php -l`
inmediata. Ningún archivo de `vendor/`, `plugin/`, `build/assets/`, `build/themes/` ni
`build/uploads/` fue tocado.

## Resumen

| Cambio | Archivos | Resultado verificado |
|---|---|---|
| Conexión compartida y constructores tolerantes | 16 | `tools/audit-di.php` → 0 incompatibilidades |
| Cifrado compatible y determinista | 1 | 5/5 pruebas de contrato de cifrado |
| Cadena de acceso (PK, comparación, intentos, salida) | 1 | `tools/verify-login-http.php` → 8/8 PASS |
| Identificadores de texto sin `(int)` | 13 | `tools/verify-login.php` → 15/15 PASS |
| Portada y páginas de archivo | 1 | `GET /` → HTTP 200 |
| Errores al log, no al navegador | 1 | revisión de código |
| Endurecimiento de identificadores SQL | 1 | `tests/Unit/SecurityPrimitivesTest.php` |
| Instrumentos de verificación | 9 archivos en `tools/` | ejecutados |
| Suite de pruebas | 6 archivos en `tests/` | 23 pruebas, 72 aserciones |

## 1. Conexión compartida — `core/src/Database/Connection.php`

Se añadió `Connection::shared(): PDO`, que devuelve **el mismo** objeto que `getConnection()`
(no abre conexiones nuevas) y lanza `RuntimeException` si la configuración no está disponible.
Es el punto único de resolución para el código heredado que no puede recibir el PDO por
constructor.

Aplicado por `tools/patch-di.php`: 25 sustituciones en 16 archivos.

- Constructores `PDO $x` → `?PDO $x = null` con `?? \PepiuoX\Database\Connection::shared()`:
  `UsersClass`, `Routers`, `GetVisitor`, `RecoveryPhrase`, `ChangePass`, `UsersForgot`,
  `UsersCodeAccess`, `Protect`, `BlogPost`, `CheckValidUser`, `SendData`, `UserVerify`,
  `Visitors`, `UserManager`.
- Propagación explícita del PDO ya resuelto: `NewUsers`, `NewscpUsers`, `UsersUpdates`,
  `UsersVerify`, `installUser` (a `UsersCodeAccess` y `Protect`), `newUser`.

## 2. Cifrado — `core/controller/GetCodeDeEncrypt.php`

`ende_crypter('encrypt', …)` usa AES-256-CBC con IV derivado (`hash('sha256', $secret_iv)`),
el formato que entienden las filas existentes y que permite la búsqueda por igualdad.
`ende_crypter('decrypt', …)` prueba CBC determinista y, si falla, AES-256-GCM con PBKDF2 de
100.000 y de 10.000 iteraciones. Aplicado por `tools/patch-crypto.php`.

Verificado: determinismo, ida y vuelta con claves de sitio y de usuario, descifrado del formato
heredado CBC y de ambos formatos GCM, y `false` ante criptograma inválido.

## 3. Cadena de acceso — `core/controller/UsersClass.php`

| Cambio | Motivo |
|---|---|
| Búsqueda de la ficha en `users` por `idUser` (clave primaria) | Comparar 4 criptogramas por igualdad nunca coincide con IV aleatorio |
| Comparación de contraseña y PIN en PHP con `hash_equals` | Verificación en tiempo constante sin oráculo SQL |
| Credenciales sin `procheck()` + aceptación de la forma escapada | Evitar el doble escape que bloqueaba el acceso |
| Contadores de intentos limitados a 10 minutos | El bloqueo era acumulativo de por vida |
| `logout()` atiende GET además de POST | El enlace de salida dejaba la sesión viva |
| `$iduv = (string) $row["idUser"]` | El identificador es texto de 64 caracteres, no entero |

Aplicado por `tools/patch-login.php` y `tools/patch-credentials.php`.

## 4. Identificadores de texto — 13 archivos

`(int)` → `(string)` en los identificadores y cambio de tipo de propiedad donde estaba
declarada como `int`:

`UsersClass`, `RecoveryPhrase`, `ChangePass`, `ChangePIN`, `CheckValidUser`,
`CheckUsersSession`, `UsersVerify`, `UserVerify`, `UsersUpdates`, `userChange`, `UsersForgot`,
`UserForgot`, `UsersChange`. Aplicado por `tools/patch-id-casts.php`.

## 5. Portada — `core/controller/PagePublic.php`

- Validación de `type` y (`link` o `title`) en lugar de la columna `url`, siempre nula.
- `allowedBasePath` correcto (`core/elements`) y denegación explícita si no resuelve.
- La rama `type = File` compara la ruta solicitada con `link`.

Respaldo adicional: `storage/backups/PagePublic.php.orig`.

## 6. Errores — `build/index.php`

`display_errors` depende de `APP_DEBUG=1`; `log_errors` queda activo siempre.

## 7. Identificadores SQL — `core/src/Security/SqlIdentifier.php`

`PATTERN` y `DATABASE_PATTERN` terminan en `\z` en lugar de `$`.

## Reversión

    # Archivos originales de cada parche
    storage/backups/patch-20261017-170454/   DI (25 cambios)
    storage/backups/patch-20261017-170531/   cifrado
    storage/backups/patch-20261017-171647/   cadena de acceso
    storage/backups/patch-20261017-172105/   casts de identificadores
    storage/backups/patch-20261017-172820/   credenciales e intentos
    storage/backups/PagePublic.php.orig      portada
    storage/backups/backup-20261017-171033.sql  base de datos completa

Los parches son idempotentes: volver a ejecutarlos informa "OMITIDO" cuando el cambio ya está.