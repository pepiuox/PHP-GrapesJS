# 04 — Hallazgos

Severidad CVSS 3.1 aproximada (`AV:N/AC:L/PR:N/UI:N/S:U` salvo indicación).

| ID | Severidad | Hallazgo | Estado |
|---|---|---|---|
| H-01 | Crítica 9.1 | 58 instanciaciones sin conexión → `ArgumentCountError` | Corregido |
| H-02 | Crítica 9.1 | Cifrado no determinista rompe la búsqueda por igualdad | Corregido |
| H-03 | Alta 8.6 | Búsqueda del usuario por criptogramas en `users` | Corregido |
| H-04 | Alta 7.5 | `(int)` sobre identificadores de texto (64 hex) → 0 | Corregido |
| H-05 | Alta 6.5 | El cierre de sesión solo atendía POST | Corregido |
| H-06 | Alta 7.5 | Doble escape de la contraseña impide el acceso | Corregido |
| H-07 | Crítica 9.1 | Claves de cifrado hardcodeadas en repositorio público | **Pendiente** |
| H-08 | Crítica 8.8 | Credenciales de base de datos en repositorio público | **Pendiente** |
| H-09 | Alta 7.4 | Contraseñas cifradas reversiblemente, no hasheadas | **Pendiente** |
| H-10 | Alta 7.5 | PIN de 6 dígitos cifrado reversible | **Pendiente** |
| H-11 | Media 6.5 | El registro guarda credenciales en claro: incompatible con el acceso | **Pendiente** |
| H-12 | Media 5.3 | `display_errors` activo en producción | Corregido |
| H-13 | Media 5.3 | Bloqueo de intentos sin ventana temporal | Corregido |
| H-14 | Media 4.4 | IP del cliente tomada de cabeceras falsificables | **Pendiente** |
| H-15 | Media 6.1 | Portada responde 400 y páginas de archivo nunca se incluyen | Corregido |
| H-16 | Media 4.3 | La sanitización elimina `url()`, `<script>` y `on*` de los diseños | **Pendiente** |
| H-17 | Baja 3.7 | `SqlIdentifier` aceptaba salto de línea final | Corregido |
| H-18 | Baja 3.1 | Faltan las páginas 403 y 500 del sistema | **Pendiente** |
| H-19 | Info | `phpstan.neon` inválido para PHPStan 2.2 | **Pendiente** |
| H-20 | Info | Duplicados y código muerto de la migración | **Pendiente** |
| H-21 | Info | `.htaccess` bloquea clientes legítimos por User-Agent | **Pendiente** |

---

## H-01 — Instanciación sin conexión en la capa de páginas (crítico, corregido)

**Evidencia.** `tools/audit-di.php` detectó 58 llamadas `new Clase()` sin argumentos sobre 14
clases cuyo constructor exige `PDO` (`UsersClass`, `UsersCodeAccess`, `Routers`, `GetVisitor`,
`Protect`, `CheckValidUser`, `Visitors`, `UserManager`, `UsersForgot`, `RecoveryPhrase`,
`ChangePass`, `BlogPost`, `SendData`, `UserVerify`).

Ejecución directa: `new UsersClass()` → `ArgumentCountError: Too few arguments ... exactly 1
expected`.

**Causa raíz.** `core/pages/**`, `core/managers/**` y `core/users/**` son plantillas incluidas
dentro de métodos de `PageSystem::safeInclude()`. En ese ámbito no existe `$conn`, y las clases
fueron migradas a inyección obligatoria de PDO sin adaptar los llamadores.

**Impacto.** HTTP 500 en `/signin/login`, `/signin/register`, `/signin/forgot-*`,
`/profile/*`, `/admin/*` y `/error/404`: el sitio público se veía, pero nadie podía entrar.

**Corrección.** `PepiuoX\Database\Connection::shared()` (devuelve el mismo singleton) y
constructores con `?PDO $conn = null` que resuelven la conexión compartida si no se inyecta.
Se conserva la inyección explícita como camino preferente.

## H-02 — Cifrado no determinista (crítico, corregido)

**Evidencia.** El acceso busca `WHERE email = :e AND mkpin = :p` comparando criptogramas, pero
`ende_crypter('encrypt', …)` genera un IV aleatorio por operación: dos cifrados del mismo texto
nunca coinciden. Además el formato había pasado de AES-256-CBC a AES-256-GCM, de modo que las
filas antiguas tampoco se podían descifrar.

Datos reales (`tools/inspect-db.php`): en `uverify`, `email`/`mkpin`/`username` son CBC
deterministas (filas antiguas) y `password` es GCM con IV aleatorio.

**Corrección.** `encrypt` vuelve al formato CBC determinista (compatible con los datos) y
`decrypt` acepta CBC, GCM-100.000 y GCM-10.000. No se cambió ninguna clave ni ningún dato.
Verificado con `tests/Unit/CryptoContractTest.php` y con `tools/verify-login.php`.

## H-03 — Búsqueda por criptograma en `users` (alta, corregido)

**Evidencia.** `SELECT * FROM users WHERE username = :u AND email = :e AND password = :p AND
mkpin = :m` comparaba cuatro criptogramas; con IV aleatorio (`users.email` lo es en la base
real) la consulta no devuelve filas nunca.

**Corrección.** Se localiza la ficha por clave primaria (`idUser = uverify.iduv`) y se comparan
contraseña y PIN en PHP con `hash_equals`. Efecto secundario deseable: desaparece el oráculo de
igualdad por SQL.

## H-04 — Identificadores tratados como enteros (alta, corregido)

**Evidencia.** `uverify.iduv`, `users.idUser`, `users_profiles.idp` y `users_info.userid` son
`char(128)` con 64 caracteres hexadecimales. `(int)` los convierte en `0`; los `UPDATE` por ese
id afectan 0 filas y la transacción de acceso aborta. Afectaba a 21 puntos en 13 archivos.

**Corrección.** Casts a `string` y tipos de propiedad `int` → `string` en
`RecoveryPhrase`, `ChangePass`, `ChangePIN`, `CheckValidUser`, `CheckUsersSession`.

## H-05 — Cierre de sesión inoperante (alta, corregido)

`UsersClass::logout()` exigía `$_POST['logout']`; el enlace del sitio hace `GET`, así que la
sesión seguía viva. Ahora se atiende también `GET`, se limpian cookies y se añade
`Cache-Control: no-store`.

## H-06 — Doble escape de la contraseña (alta, corregido)

**Evidencia.** El registro guardaba la contraseña después de `htmlspecialchars` (procheck) y el
acceso volvía a escapar el valor recibido: `procheck(procheck(P)) != procheck(P)`. Comprobado
con la contraseña real del usuario: `hash_equals(procheck(P), P)` → **distinto**.

**Corrección.** Las credenciales se comparan sin escapar; por compatibilidad se acepta también
la forma escapada. Las credenciales no son HTML y no deben transformarse.

## H-07 — Claves de cifrado hardcodeadas (crítico, pendiente)

**Evidencia.** `core/config/define.php` define `SECURE_TOKEN` (128 caracteres) y `SECURE_HASH`
y está **versionado en el repositorio**. `git ls-files` confirma que está rastreado.

**Impacto.** Quien tenga el repositorio tiene la clave del sitio: puede descifrar correos,
usuarios y PIN, y falsificar valores cifrados. El `SECURE_TOKEN` de `define.php` difiere del de
`site_security` en la base real, así que el daño inmediato depende de la ruta que lo use
(`GetVisitor`, `newUser`, credenciales de correo), pero es una clave publicada.

**Remediación propuesta.** Rotar `SECURE_TOKEN`/`SECURE_HASH`, re-cifrar los datos con la clave
nueva en una migración con respaldo previo y sacar los secretos del código (`.env` +
`core/config/server.php` no versionado). Requiere autorización y ventana de mantenimiento.

## H-08 — Credenciales de base de datos en el repositorio (crítico, pendiente)

`core/config/server.php` está versionado y contiene usuario y contraseña reales de MariaDB.
Remediación: rotar la contraseña del usuario de base de datos, mover la configuración a un
archivo no versionado y añadirlo a `.gitignore`.

## H-09 / H-10 — Contraseñas y PIN reversibles (alta, pendiente)

Las contraseñas se guardan cifradas (reversible) con las claves del usuario y se comparan tras
descifrar; los PIN de 6 dígitos se cifran con la clave del sitio y se comparan por igualdad.
Ambos esquemas permiten recuperar el secreto original si se filtra una clave. Remediación:
`password_hash`/`password_verify` con rehash progresivo y columna de índice ciego (HMAC) para
las búsquedas, manteniendo el cifrado aleatorio de los datos sensibles.

## H-11 — Registro incompatible con el acceso (media, pendiente)

`core/controller/newUser.php` inserta en `uverify` el usuario y el correo **en claro** (usa
`$username`/`$email` sin cifrar) y en `users` el PIN en claro, mientras el acceso busca los
valores cifrados. Todo usuario creado por esa ruta queda sin poder iniciar sesión. Remediación:
cifrar con las claves del sitio en `uverify` y con las del usuario en `users`, igual que
`core/application/installer/installUser.php`.

## H-12 — Errores visibles (media, corregido)

`build/index.php` fijaba `error_reporting(E_ALL)` y `ini_set('display_errors', 1)`. Ahora solo
se muestra con `APP_DEBUG=1`; el resto va al log.

## H-13 — Bloqueo sin ventana temporal (media, corregido)

El mensaje decía "solo se permiten 3 intentos en 10 minutos", pero el contador sumaba todas las
filas históricas de `ip` para el correo: tras tres fallos en cualquier momento la cuenta quedaba
bloqueada indefinidamente. Ahora ambos contadores filtran `timestamp >= NOW() - INTERVAL 10
MINUTE`.

## H-14 — IP del cliente falsificable (media, pendiente)

`getUserIP()` acepta `HTTP_CF_CONNECTING_IP` y `X-Forwarded-For` sin comprobar que la petición
venga de un proxy propio: un cliente puede declarar la IP que quiera y eludir bloqueos por
dirección.

## H-15 — Portada 400 y páginas de archivo inalcanzables (media, corregido)

**Evidencia.** `PagePublic::viewPagePublic()` exigía `isset($rp['url'])`, pero en la base real
`pages.url` es `NULL` en las 6 filas: la portada devolvía 400 con cuerpo vacío. Además
`allowedBasePath = realpath(__DIR__ . '/../core/elements')` apuntaba a `core/core/elements`
(no existe) y devolvía `false`, de modo que `safeInclude()` rechazaba siempre, y la rama
`type = File` comparaba `REQUEST_URI` con la columna nula.

**Corrección.** Se valida `type` y (`link` o `title`); la base permitida es
`core/elements`; la rama `File` compara con `link`; si la base no resuelve, se deniega la
inclusión. Verificado: `GET /` → HTTP 200.

## H-16 — Sanitización que rompe los diseños (media, pendiente)

`sanitizeCSS()` elimina `url(...)` (fondos e imágenes), y `sanitizeHTML()` elimina `<script>` y
todos los atributos `on*`, incluidas las interacciones legítimas creadas en el builder. Es una
decisión de producto: si el editor permite HTML propio, la política correcta es CSP por
nonce/origen y no recortar contenido en servidor.

## H-17 — Identificadores SQL con salto de línea (baja, corregido)

`PATTERN` y `DATABASE_PATTERN` terminaban en `$`, que en PCRE acepta un salto de línea final.
Se sustituyó por `\z`.

## H-18 — Páginas de error ausentes (baja, pendiente)

`PageSystem::render403()` y el caso 500 incluyen `core/pages/error_pages/403.php` y `500.php`,
que no existen: la respuesta se envía vacía. `404.php` sí existe.

## H-19 — Configuración de PHPStan inválida (informativa, pendiente)

`phpstan.neon` usa claves eliminadas en PHPStan 2.x (`checkMissingIterableValueType`,
`checkGenericClassInNonGenericObjectType`, `checkAdvancedUnionTypes`) y declara
`paths: src, tests`, que no existen en la raíz. `composer phpstan` termina en error de
configuración. Se ejecutó PHPStan apuntando a `core/src`.

## H-20 — Duplicados y código muerto (informativa, pendiente)

Convivien: `Session.php` y `SessionManager.php` (tercera implementación de acceso, sin uso
detectado), `Auth.php` (basada en `users.id` numérico, esquema distinto), `UsersForgot` vs
`UserForgot`, `UsersChange` vs `userChange`, `Page0/Page1`, `PageModel0..2`,
`PagesController-ai`. Es deuda de la migración y riesgo de que un arreglo se aplique a la copia
equivocada.

## H-21 — Bloqueo de clientes por User-Agent (informativa, pendiente)

El `.htaccess` raíz devuelve 403 a User-Agents que contienen `curl`, `wget`, `python`, `java`,
`mysql`, etc. Comprobado: `curl http://localhost:150/` → 403. Rompe integraciones, monitoreo y
pruebas automatizadas legítimas.