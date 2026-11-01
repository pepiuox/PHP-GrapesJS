# Endurecimiento de seguridad (`core/`)

> **Documento de referencia permanente.** Léelo junto con [`PDO-MIGRATION.md`](PDO-MIGRATION.md).
> Si cambias una regla, actualiza también el inventario de §6.

---

## 1. Objetivo y estado

| | |
|---|---|
| **Ámbito** | `core/` |
| **Objetivo** | Eliminar la inyección SQL y demás fallos de seguridad, garantizar compatibilidad con PHP 8.3/8.4 y dotar al proyecto de una arquitectura de carga moderna (namespaces + autoload). |
| **Superficie detectada** | **990 sentencias SQL con variables interpoladas en 220 archivos** (medición inicial). |
| **Estado** | Ver §6 para el progreso por zona. |

**Regla de oro:** ningún valor que venga del usuario (o de la base de datos) se concatena
nunca en el texto SQL. Sólo se concatenan **identificadores validados** (nombres de tabla/columna).

---

## 2. Reglas de parameterización (obligatorias)

### 2.1 Valores → siempre por placeholder

```php
// ❌ ANTES
$sql = "SELECT * FROM galleries WHERE idGal = '$id'";
$row = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);

// ✅ DESPUÉS
$sql = "SELECT * FROM galleries WHERE idGal = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
```

Se admiten tanto placeholders nombrados (`:id`) como posicionales (`?`). **No mezclar** ambos
estilos en la misma sentencia. Si el código ya usaba `?`, consérvalo para minimizar el diff.

### 2.2 `INSERT` / `UPDATE` / `DELETE`

```php
// ❌ ANTES
$sql = "UPDATE `my_info` SET `first_name` = '$fn', `age` = '$ag' WHERE idPro = '1'";

// ✅ DESPUÉS
$sql = "UPDATE `my_info` SET `first_name` = :fn, `age` = :ag WHERE idPro = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':fn' => $fn, ':ag' => $ag, ':id' => $idPro]);
```

Se conserva la lista de columnas (es fija, escrita en el código). Si la lista de columnas
se construye dinámicamente, ver §3.1.

### 2.3 `LIKE`

El comodín **no** va en el SQL, va en el parámetro:

```php
// ❌ $sql = "... WHERE title LIKE '%$str%'";
// ✅
$sql = "... WHERE title LIKE :q";
$stmt->execute([':q' => '%' . $str . '%']);
```

### 2.4 `IN (...)`

No se puede meter un array en un placeholder. Se generan tantos placeholders como elementos:

```php
// ❌ $sql = "SELECT * FROM pages WHERE id IN ($ids)";
$ids = array_map('intval', (array) $ids);          // o validar como enteros
$ph  = implode(',', array_fill(0, count($ids), '?'));
$sql = "SELECT * FROM pages WHERE id IN ($ph)";
$stmt = $conn->prepare($sql);
$stmt->execute($ids);
```

Si el array puede venir vacío, hay que cortocircuitar (`IN ()` es SQL inválido):
`if ($ids === []) { return []; }`.

### 2.5 `LIMIT` / `OFFSET`

Con `PDO::ATTR_EMULATE_PREPARES => false` el driver **rechaza strings** en `LIMIT`:

```php
$stmt = $conn->prepare("SELECT * FROM pages ORDER BY id LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', (int) $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', (int) $offset,  PDO::PARAM_INT);
$stmt->execute();
```

> Nunca uses `execute([':lim' => $perPage])` para `LIMIT`: llega como string y falla.
> Si el valor ya está forzado a entero y validado, concatenarlo también es aceptable
> (`LIMIT " . (int) $n`), pero `bindValue` es la opción preferida.

### 2.6 `ORDER BY` y nombres de columna

`ORDER BY :col` **no funciona** (se convierte en un literal, no en un identificador).
Se valida contra lista blanca:

```php
use PepiuoX\Security\SqlIdentifier;

$col = SqlIdentifier::fromWhitelist($orderBy, ['id', 'title', 'created_at']);
$dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
$sql = "SELECT * FROM pages ORDER BY " . SqlIdentifier::quote($col) . " $dir";
```

### 2.7 Errores: no filtrar detalles al usuario

```php
// ❌ echo "Error: " . $e->getMessage();   // filtra SQL, rutas y esquema
// ✅
error_log('...: ' . $e->getMessage());     // el detalle sólo al log
$_SESSION['ErrorMessage'] = 'No se pudo completar la operación.';  // al usuario, genérico
```

---

## 3. Casos especiales

### 3.1 Identificadores dinámicos (tabla/columna desde fuera)

Cualquier nombre de tabla o columna que venga de `$_POST`/`$_GET`/`$_REQUEST` **debe** pasar
por `PepiuoX\Security\SqlIdentifier` (`core/src/Security/SqlIdentifier.php`):

```php
use PepiuoX\Security\SqlIdentifier;

$tbl  = SqlIdentifier::assert($_POST['tbl']);              // patrón estricto
$cols = SqlIdentifier::filter(explode(',', $_POST['cols'])); // lista depurada

if ($cols === []) { /* abortar con error controlado */ }

$sql = 'SELECT ' . implode(', ', SqlIdentifier::quoteList($cols))
     . ' FROM ' . SqlIdentifier::quote($tbl)
     . ' WHERE id = :id';
```

Cuando el conjunto de valores posibles es finito, **preferir lista blanca**
(`fromWhitelist()`) al patrón genérico: p. ej. las tablas del CMS o las columnas reales
leídas de `INFORMATION_SCHEMA`.

### 3.2 Rutas y `require`/`include` construidos con variables

Si el nombre de archivo viene de fuera, validar contra lista blanca y construir la ruta con
`basename()` y un prefijo fijo. **Nunca** concatenar segmentos sin validar (LFI).

### 3.3 Consultas que ya eran seguras

Si una sentencia no interpola variables, **no la toques**. El objetivo es el diff mínimo.

### 3.4 Lo que NO se debe cambiar

- El texto SQL en sí (tablas, columnas, `JOIN`, `ORDER BY`, `LIMIT`, `GROUP BY`).
- La lógica de negocio, los nombres de variables y las funciones existentes.
- El HTML/JS y el formato de las plantillas.
- El comportamiento observable: una consulta debe devolver exactamente lo mismo.
- No añadir `try/catch` nuevos salvo que ya existiera uno en la zona.

---

## 4. Errores que hay que evitar

| Error | Por qué importa |
|---|---|
| Pasar el `%` de un `LIKE` como parte del SQL | Anula la parametrización. |
| `LIMIT :n` con `execute()` | Error del driver con prepares nativos. |
| Mezclar `?` y `:nombre` en una sentencia | `PDOException` / bind incorrecto. |
| Reordenar parámetros al pasar de `bind_param` a `execute([])` | Silenciosamente asigna valores a columnas equivocadas. |
| Escapar con `addslashes`/`htmlspecialchars` en lugar de parametrizar | No protege; además corrompe los datos. |
| Cambiar el tipo devuelto (p. ej. `rowCount()` donde se esperaba un array) | Rompe la vista. |
| Validar el identificador *después* de usarlo | Inútil. |
| Convertir a `prepare()` una query que ya usaba `?` y renumerar mal | Rompe los datos. |

---

## 5. `protect()` y saneado

- `protect()` (`core/config/function.php`) es saneado de **salida HTML** (`htmlentities` +
  `htmlspecialchars`). **No** es una defensa contra inyección SQL y **no** sustituye a `prepare()`.
- Ya no llama a `mysqli_real_escape_string`.
- Para escapar en la salida HTML de datos de BD, preferir `htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.

---

## 6. Inventario y progreso

> **La medición inicial de §1 estaba sobreestimada.** El escaneo contaba *cualquier* línea con
> `SELECT/INSERT/UPDATE/DELETE` que contuviera `$`, lo que incluía líneas donde el `$` sólo
> aparecía en el nombre de la variable PHP (`$query`, `$stmt`) o en un nombre de método
> (`updateLastLogin`). La verificación real, archivo por archivo y con tokenizador de PHP,
> dio este resultado:

| Zona | Valor interpolado (inyección) | Identificadores dinámicos | Estado |
|---|---|---|---|
| `core/controller/` (auth/user/session, 61 archivos) | **0** (ya estaba parametrizado) | 115 sitios validados + 2 `LIMIT` corregidos | ✅ |
| `core/controller/` (CRUD/datos/query-builder, 59 archivos) | **0** (ya estaba parametrizado) | 19 sitios validados; SQL generado ahora parametrizado | ✅ |
| `core/blocks/`, `core/config/`, `core/application/` | **76 sentencias** corregidas | 4 sitios validados | ✅ |
| `core/includes/`, `core/users/`, `core/templates/`, `core/scripts/`, `core/file-manager/` | **23 sentencias** corregidas | 0 | ✅ |
| `core/managers/` (+`views/`, `testorder/`), `core/elements/` | pendiente | pendiente | 🟨 en curso |

**Total:** `SqlIdentifier` se usa en **98 puntos**; `LIMIT` mal ligado con `execute([...])`: **0 restantes**.

---

## 7. Inventario de seguridad verificado

Auditoría ejecutada sobre `core/` (excluyendo las librerías de terceros `filemanager/` y `PHPMailer/`).

### 7.1 Hallazgos REALES (por severidad)

| # | Hallazgo | Nº | Severidad | Estado |
|---|---|---|---|---|
| S1 | Inyección SQL por interpolación de valores | 990 (220 archivos) | 🔴 Crítica | 🟨 en curso |
| S2 | `extract($_POST)` / `extract($_FILES)` — sobreescribe variables locales (incluido `$conn`, `$sql`, `$tbl`) | 13 | 🔴 Crítica | ⬜ |
| S3 | `unserialize()` sobre datos de BD → *object injection* — `core/controller/DatabaseCacheDriver.php:41` | 1 | 🟠 Alta | ⬜ |
| S4 | `rand()` / `mt_rand()` usados para tokens, PINs y claves (no criptográficos) | 11 | 🟠 Alta | ⬜ |
| S5 | `$_SERVER['PHP_SELF']` impreso sin escapar → XSS reflejado | 28 | 🟠 Alta | ⬜ |
| S6 | `echo`/`print` directo de `$_GET/$_POST/$_REQUEST` → XSS almacenado/reflejado | 29 | 🟠 Alta | ⬜ |
| S7 | `header('Location: …')` construido con `HTTP_HOST`/entrada del usuario → *open redirect* + *host header injection* | 6 | 🟡 Media | ⬜ |
| S8 | `setcookie()` sin `httponly`/`secure`/`samesite` revisados | 21 | 🟡 Media | ⬜ |
| S9 | `move_uploaded_file()` — revisar validación de extensión/MIME y ruta destino | 20 | 🟡 Media | ⬜ |
| S10 | `file_get_contents()` con ruta construida con variables (caché) — *path traversal* | 15 | 🟡 Media | ⬜ |

### 7.2 Falsos positivos descartados (verificados a mano)

| Patrón | Por qué NO es un problema |
|---|---|
| `preg_replace('/…/e')` (4 coincidencias) | Los patrones son `'/expression\s*\(/i'` y `'/eval\s*\(/i'`; el `/e` detectado es el inicio de `expression`/`eval`, **no** el modificador. |
| `shell_exec`/`system`/`exec` (19, salvo vendidos) | Todas las coincidencias no-vendidas son `PDO::exec()` (`$conn->exec($sql)`). |
| `${var}` en cadenas (17) | Son *template literals* de JavaScript dentro del HTML, no interpolación PHP. |
| `utf8_encode()` (2) | Sólo en la librería de terceros `files.photo.gallery`. Deprecado en 8.2, eliminado en 9.0. |
| `phpinfo()` (2) | Sólo en la librería de terceros. |
| `eval()` (3) | Sólo comentarios que documentan su eliminación. |

### 7.3 Compatibilidad PHP 8.3 / 8.4 — verificado LIMPIO

| Comprobación | Resultado |
|---|---|
| Parámetros *nullable* implícitos (`Type $x = null`) — deprecado en 8.4 | **0** |
| Escritura en propiedades no declaradas (deprecado en 8.2) | **0** |
| `each()` (eliminado en 8.0) | **0** (corregidos 19 en la migración PDO) |
| `create_function`, `ereg`/`eregi`, `get_class()` sin args, `money_format`, offsets `$s{0}` | **0** |
| `FILTER_SANITIZE_STRING`, `strftime`, `implode($array,$glue)` | **0** |
| `display_errors` activado en código | **0** |
| `__autoload()` (eliminado en 8.0) | **0** (sustituido por el autoloader único) |
| `php -l` sobre los 554 archivos de `core/` | 553 OK, 1 fallo previo (`core/controller/test1.php`) |

---

## 8. Verificación

### 8.1 Sintaxis

```powershell
Get-ChildItem core -Recurse -File -Filter *.php | ForEach-Object { php -l $_.FullName }
```

### 8.2 Restos de SQL interpolado

```powershell
# Busca SQL con variables interpoladas: comillas simples/dobles alrededor de $var dentro de una sentencia
Get-ChildItem core -Recurse -File -Filter *.php |
  Where-Object { $_.FullName -notmatch '\\filemanager\\|\\PHPMailer\\' } |
  Select-String -Pattern '=\s*"[^"]*(SELECT|INSERT|UPDATE|DELETE)[^"]*\$'
```

Cualquier coincidencia debe revisarse a mano: puede ser un identificador validado
(aceptable, con `SqlIdentifier`) o una interpolación de valor (inaceptable).

### 8.3 Marcadores de patrón inseguro

```powershell
Select-String -Path (Get-ChildItem core -Recurse -File -Filter *.php | ForEach-Object FullName) `
  -Pattern 'mysql_query|mysqli_query|real_escape_string|addslashes'
```

---

## 9. Bugs corregidos (registro)

Todos verificados con `php -l` y, cuando aplica, con prueba en tiempo de ejecución.
Se listan aquí porque son el motivo por el que el código ahora funciona, no sólo por seguridad.

### 9.1 Fatales (rompían la página)

| Archivo | Defecto | Corrección |
|---|---|---|
| `core/blocks/about/about.php` | `count($row)` sobre el resultado de `fetch()` → **`TypeError`** cuando no hay filas | `($row === false) ? 0 : count($row)` |
| `core/blocks/about/about.php` | Constantes `whatsapp` y `ADDRESS_CONTACT` **no definidas** → `Error` fatal en PHP 8 | `WHATSAPP` y `EMAIL_CONTACT` (las definidas en `define.php`) |
| `core/blocks/profile/profile.php` | Constante `skype` no definida (`SKYPE` sí lo está) → `Error` fatal | `SKYPE` |
| `core/blocks/profile/profile.php` | `$row['x']` sobre `false` → avisos; bloque sin datos | `?: array_fill_keys([...], '')` |
| `core/users/views/{info,locations,privacy,profile,verifications}.php` | `$conn->prepare($sql)` con `$sql` **indefinido** (la sentencia está en `$query`) → fatal | `prepare($query)` |
| (los mismos 5) | `$id` **nunca definido** en el `execute()` → `WHERE … = NULL`, el UPDATE no afectaba a ninguna fila y se perdía en silencio | identificador explícito y validado desde la sesión + guarda `if ($rowId !== null)` |
| (los mismos 5) | `$updated` (valor del POST) era **sobrescrito** por el objeto statement antes de usarse como parámetro | el statement pasa a `$stmt` |
| `core/users/views/{info,locations,privacy,profile}.php` | `?> ` duplicado/etiquetas mal cerradas | revisado, sin cambios de estructura |
| `core/scripts/cache_manager.php` | `require` de `includes/PageCacheManager.php` **inexistente**; `config/database.php` con minúscula; `getDatabaseConnection()` **no definida en ninguna parte** | autoloader + `PepiuoX\Database\Connection` |

### 9.2 De lógica (no fatales, pero el código no hacía nada)

| Archivo | Defecto | Corrección |
|---|---|---|
| `core/users/controls/update_locations.php` | `UPDATE users_locations <columnas>` **sin `SET`** → error de sintaxis SQL en cada envío | añadido `SET` |
| `core/users/controls/update_info.php` | `address2 = ?WHERE` **sin espacio** → error de sintaxis SQL | `address2 = ? WHERE` |
| `core/users/controls/update_info.php` | `$id` y `$ucode` no definidos (el UPDATE y el SELECT no encontraban nada) | resueltos desde la sesión con la convención del proyecto |
| `core/users/infodetails.php` | `if ($conn->query($sql) === TRUE)` — `PDO::query()` **nunca** devuelve `true`, así que la rama de éxito (el redirect) era inalcanzable | `if ($stmt->execute([...]) === TRUE)` (correcto: `execute()` sí devuelve bool) |
| `core/users/infodetails.php` | El error de BD se mostraba al usuario (`$_SESSION['error'] = 'Error: ' . $conn->errorInfo()[2]`) | detalle sólo a `error_log()`; mensaje genérico al usuario |
| `core/includes/file-functions.php` | `LIMIT :limit` ligado con `execute([...])` → falla con `EMULATE_PREPARES = false` | `bindValue(':limit', (int) $limit, PDO::PARAM_INT)` |
| `core/controller/UserManager.php` | igual, en `getLoginAttempts()` | `bindValue(..., PARAM_INT)` |
| `core/controller/UsersSearches.php` | igual, en `getRecentSearches()` | `bindValue(..., PARAM_INT)` |
| `core/application/installer/install.php` | `CREATE DATABASE` concatenaba `$_POST['dbname']` sin validar | `SqlIdentifier::quoteDatabase()` |
| `core/application/installer/install.php` | Las listas `SET` se **acumulaban** entre los bloques `Update` y `UpdateCode` | reinicio por bloque |
| `core/application/installer/install.php` | Una clave de `$_POST` no-identificador lanzaba una excepción **sin capturar** | las claves inválidas se descartan y se registran; si no queda ninguna, error controlado |
| `core/cache/cachestart.php` / `cacheend.php` | Clave de caché de `basename($_SERVER['PHP_SELF'])` (nombre de archivo influenciable); `unlink()` sin comprobar; buffer abierto y **nunca cerrado** (nunca escribían el caché) | hash de script+query, ruta absoluta, escritura atómica, sólo GET anónimos (evita envenenamiento de caché → XSS almacenado) |
| `core/config/.htaccess` | Sintaxis Apache 2.2 (`Order`/`Deny`): en Apache 2.4 sin `mod_access_compat` devuelve **500** en el directorio que guarda `server.php`; además sólo protegía `server.php` | sintaxis 2.4 (`Require all denied`) + respaldo 2.2 + más archivos |
| `core/controller/test1.php` | `$user = [...];` — marcador sin completar → `Parse error` (bloqueaba cualquier análisis estático) | **eliminado** (no tenía referencias) |
| `core/manager*/Autoload.php` (×5), `core/config/loader.php`, `core/dispatcher.php`, `core/config/Database.php` | infraestructura de arranque | ver `PDO-MIGRATION.md` §10 |

### 9.3 Constantes no definidas (auditoría)

Se escanearon las 34 constantes de `define.php` contra todos sus usos:
**0** usos en minúscula; **0** constantes del proyecto sin definir.
Los falsos positivos descartados fueron `SMTP::DEBUG_SERVER`/`SMTP::DEBUG_OFF` (constantes de PHPMailer),
`"SITE_URL"` (clave de array, no constante) y `SUPERADMIN_NAME` (atributo HTML `for=`).

> ⚠️ **Pendiente:** `core/managers/adduser.php:10` usa `if (EMAIL_WELCOME)` **sin** la guarda
> `defined()` que sí tiene `Session.php:286`. Si la constante no está definida en la BD,
> es un `Error` fatal.

### 9.4 Estado de los pendientes

| # | Tarea | Nº | Estado |
|---|---|---|---|
| P1 | `extract($_POST/$_FILES)` → `EXTR_SKIP` (no sobrescribe locales) | 13 | ✅ hecho |
| P2 | `rand()`/`mt_rand()` → `random_int()` | 11 | ✅ hecho (sólo quedaban 4 reales; el resto eran comentarios ya corregidos) |
| P3 | `$_SERVER['PHP_SELF']` — **revisado: falsos positivos** | 28 | ✅ verificado: son `basename()` para comparar, `dirname()` para construir la URL o `htmlspecialchars()`; **no se imprimen** |
| P4 | `echo` de `$_GET/$_POST` — **revisado: falsos positivos en zonas libres** | 29 | 🟨 los de `core/managers/` quedan por revisar uno a uno |
| P5 | Integrar `Csrf::field()` + `Csrf::check()` | — | 🟨 **emisión COMPLETA**: 0 formularios POST sin token (96 archivos). Arregla los flujos que estaban ROTOS (login, registro, recuperación, contacto). **Falta la mitad de validación** en el CRUD del panel (`core/managers/**`) y en los formularios generados por `MyCRUD` |
| P6 | `setcookie()`: `samesite`/`secure` condicional | 21 | ✅ hecho (sólo 2 sitios **establecían** cookie sin `samesite`; los demás eran borrados, donde el atributo es irrelevante) |
| P7 | Validación de subidas (`move_uploaded_file`) por MIME/extensión y ruta | 20 | ✅ **hecho**: 12 puntos cableados a `PepiuoX\Security\Upload`; `MediaManager` ya no guarda el tipo del cliente |
| P8 | `unserialize()` → `allowed_classes => false` | 1 | ✅ hecho |
| P9 | Guarda `defined('EMAIL_WELCOME')` en `adduser.php` | 1 | ✅ hecho |
| P10 | `$_SERVER['HTTP_HOST']` en redirecciones (open redirect) | 6 | ✅ hecho (`core/index.php`, `installer/index.php`, `redirect()`) |
| P11 | `$where` como fragmento SQL en `Database::update/delete` y `FormCRUD` (riesgo estructural de API) | — | ⬜ documentado: requiere cambiar la firma pública |
| P12 | `htmlspecialchars` aplicado **antes** de guardar (`User::updateProfile`, `UserManager::create`) → datos corruptos en BD | — | ⬜ pendiente (afecta a datos ya almacenados) |
| P13 | `core/managers/**` + `core/elements/**`: parametrización + identificadores | 132 sentencias | ✅ hecho |
| P14 | Columna `pos` en `editmenu.php` y `post` en `testorder/menu.php` no existen en `pages` | 2 | ⬜ requiere decisión (¿`position`/`sort`?) |
| P15 | `core/templates/navbar.php` y `admin_navbar.php` tienen formularios "Crear Página" (`create_page`) **sin ningún handler** que lea `$_POST['create_page']` | 3 | ⬜ UI muerta; requiere decisión de producto |
| P16 | `logout()` y `Profile()` (`UsersClass.php`) no validan CSRF | 2 | ⬜ riesgo bajo (forzar el logout no es toma de cuenta) |
| P17 | `core/managers/users.php:55` y `core/includes/functions.php:68` comparan el token con `!==` en lugar de `hash_equals()` | 2 | ⬜ pendiente |
| P18 | Comparación extensión↔MIME: `Upload` acepta un PNG llamado `.jpg` (ambos son tipos de imagen permitidos y el MIME se valida por contenido) | — | ⬜ opcional (endurecimiento, no vulnerabilidad) |
| P19 | 15 archivos con `<form>` **sin `method` explícito** (envían por GET, así que no se les puede poner CSRF): `FormBuilder.php`, `MyCRUD.php`, `Templates.php`, `elements/menu.php`, `elements/navmbr.php`, `file-manager/index.php`, `managers/components/*` (7), `views/menu_sort.php`, `templates/books.php` | 15 | ⬜ revisar si debían ser POST (si lo eran, **ya estaban rotos**: un formulario sin `method` envía GET y un handler que lee `$_POST` nunca se ejecuta) |
| P20 | `Csrf::check()` en los handlers del panel (`core/managers/**`) y del área de usuario | — | ✅ **hecho de forma central** con `PepiuoX\Security\Csrf::guardPost()` (ver §15), invocada en los 3 despachadores (`managers/dashboard.php`, `users/userprofile.php`, `users/view.php`) → cubre TODOS los handlers de las vistas incluidas después |

### 9.5 Correcciones a este mismo documento

Dos afirmaciones de versiones anteriores eran **incorrectas** y quedan rectificadas:

| Afirmación anterior | Realidad verificada |
|---|---|
| «`core/managers/updatetmp.php` vincula `$id`, que nunca se define» | `$id` **sí** está definido: el archivo es un *include* y lo aporta su único incluirdor, `core/managers/views/crud.php:128` (`$id = $_GET["id"]`). Aun así se ha hecho autónomo (`$id ?? $_GET['id']`). |
| «28 usos de `PHP_SELF` sin escapar» y «29 `echo` de superglobales» | En las zonas revisadas son **falsos positivos**: comparaciones con `basename()`, construcción de URL con `dirname()` o `htmlspecialchars()` ya aplicado. |

---

## 11. Subida de archivos (`PepiuoX\Security\Upload`)

### 11.1 El fallo

Los puntos de subida construían el destino con **el nombre que envía el cliente**:

```php
$file = 'uploads/' . $_FILES['files']['name'][$i];   // ❌
move_uploaded_file($file_tmp, $file);
```

Consecuencia: un nombre como `../../core/config/server.php` escribía **fuera** del directorio de
subidas (**path traversal → escritura arbitraria de archivos**, y potencial RCE si el destino es
ejecutable). Además:

- El tipo se validaba con `$_FILES[...]['type']`, una cabecera que **controla el cliente**.
- La extensión se comparaba a veces **sin normalizar a minúsculas** (`.JPG` se rechazaba: bug funcional).
- No había comprobación de `is_uploaded_file()` ni límite de tamaño homogéneo.

### 11.2 La solución

`core/src/Security/Upload.php` centraliza una única implementación auditada:

| Regla | Detalle |
|---|---|
| El nombre del cliente **nunca** forma parte de la ruta | sólo se lee su extensión |
| Nombre final | `bin2hex(random_bytes(8))` + extensión validada |
| Tipo real | `finfo` sobre el archivo temporal (contenido), no la cabecera del cliente |
| Comprobaciones | `is_uploaded_file()`, código de error, tamaño máximo |
| Directorio | debe existir y ser escribible; se verifica que la ruta final siga dentro de él |
| Permisos | `chmod 0644` tras mover |

API: `Upload::storeImage($file, $dir, $maxBytes, &$error)`, `Upload::store(...)`,
`Upload::extension()`, `Upload::mime()`, `Upload::safeName()`, `Upload::validate()`,
`Upload::assertSafeFilename()`.

Probado: rechaza `../../etc/passwd`, `a/b.png`, cadenas con `\0`, `..` y `''`;
`extension('x.PHP.jpg') === 'jpg'`; genera nombres aleatorios de 16 hex + extensión.

### 11.3 Cableado en el proyecto (12 puntos de subida, 10 archivos)

| Archivo | Antes (destino) | Ahora |
|---|---|---|
| `core/users/upload.php` | `'uploads/' . $_FILES['files']['name'][$i]` | `storeImage($file, 'uploads/')` |
| `core/users/controls/update_image.php` | `$_SESSION['folderusr'] . basename(...)` | `storeImage(...)`; la BD guarda el **basename** |
| `core/managers/gallery.php` | `'images/' . basename(...)` | `storeImage($file,'images/',500000)` |
| `core/managers/imageload.php` | `DOCUMENT_ROOT.'/build/uploads/'.basename(...)` | `storeImage(...)` |
| `core/managers/views/blog_posts.php` (×2) | `"../uploads/" . $file_name` | `storeImage(..., "../uploads/", 4000000)` |
| `core/managers/views/pages.php` (×2) | `"../uploads/" . $file_name` | `storeImage(...)`; la BD (`pages.image`) guarda el nombre generado |
| `core/managers/views/mailers.php` | `"../uploads/" . basename(...)` | `storeImage(...)`; la BD guarda el basename |
| `core/managers/views/settings.php` | ídem | ídem |
| `core/managers/upImage.php` | nombre ya aleatorio | `storeImage(...)` |
| `core/managers/upload_image.php` | nombre ya aleatorio | `storeImage(...)` |

Los **límites de tamaño** y los **directorios** se conservaron literalmente. En
`update_image.php` la comprobación anterior leía `$_FILES["file"]["size"]` (clave
equivocada), así que **no había límite efectivo**: se aplica `DEFAULT_MAX_BYTES` (2 MiB).

Cinco de esos archivos son **puntos de entrada autónomos** (no pasan por
`core/config/loader.php`), así que necesitaban poder cargar el helper por sí mismos: se les
añadió `require_once dirname(__DIR__) . '/Autoload.php';`.

### 11.4 `is_writable()` NO se usa como puerta

`Upload::store()` exige que el directorio **exista** (`is_dir()`), pero **no** comprueba
`is_writable()`. En varios entornos (sandboxes, ACL de Windows, sistemas de archivos en
red) `is_writable()` da **falsos negativos** y bloquearía todas las subidas. La
comprobación fiable es el resultado de `move_uploaded_file()`, que es la que decide; si
falla, se registra la causa y se devuelve un mensaje genérico.

### 11.5 `MediaManager`: tipo controlado por el cliente

`core/controller/MediaManager.php` persistía `$file['type'] ?? $extension` (cabecera del
**cliente**) en la columna `media.file_type`, que además se usa para filtrar en
`getUserMedia()`. Corregido: se guarda la **extensión validada** y el MIME real de `finfo`.

`FileManager.php` ya era correcto (nombre generado por el servidor, MIME por `finfo`, sin
uso de `$_FILES[...]['type']`). Ninguno de los dos construía la ruta con el nombre del cliente.

---

## 12. Divulgación de información: `display_errors` en producción

### 12.1 El fallo

`core/config/error_report.php` se incluye en **cada petición** (desde
`core/config/dbconnection.php`) y hacía, sin ninguna condición:

```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
```

`ini_set()` se ejecuta **después** de que Apache aplique el
`php_flag display_errors Off` del `.htaccess`, por lo que **lo sobrescribía**. Resultado:
cualquier visitante podía ver rutas absolutas del servidor, fragmentos de SQL, trazas de
pila y versiones de PHP/MySQL — información de gran valor para preparar un ataque.

Además el archivo era incoherente: llamaba a `error_reporting()` **cuatro veces** (sólo
contaba la última) y a `ini_set("error_reporting", …)`, que **no es una directiva válida**
y se ignoraba silenciosamente.

### 12.2 La solución

- Los errores se **registran siempre** (`log_errors=On`, `error_reporting(E_ALL)`).
- Sólo se **muestran** si la aplicación está en desarrollo, detectado por
  `DEBUG` → `APP_DEBUG` → `APP_ENV`, con **`production`/`prod` siempre ganando**.
- **Por defecto: no mostrar** (valor seguro si no hay nada configurado).

Verificado:

| Entorno | `display_errors` |
|---|---|
| (nada configurado) | `0` |
| `APP_DEBUG=true` | `1` |
| `APP_ENV=production` + `APP_DEBUG=true` | `0` (gana `production`) |

> ⚠️ Dato relacionado: `composer.json` incluye `vlucas/phpdotenv`, pero **el proyecto nunca
> carga el archivo `.env`** (no hay ninguna llamada a `Dotenv`). Por eso `.env.example`
> (`APP_ENV=local`, `APP_DEBUG=true`) no tiene efecto y la detección usa variables de
> entorno reales del servidor o la constante `DEBUG`.
>
> `expose_php` **no** se puede cambiar con `ini_set()` (es `PHP_INI_SYSTEM`); se controla
> desde `php.ini` o con `php_flag expose_php Off` del `.htaccess`.

---

## 13. Inclusión dinámica de plantillas (LFI / path traversal)

### 13.1 El fallo

Las 9 plantillas públicas de `core/templates/` construían rutas de inclusión con valores
que vienen de la base de datos:

```php
include 'require/' . $myMenu['type_menu'] . '.php';
require_once 'blocks/' . $block['type_block'] . '/' . $block['type_block'] . '.php';
```

`type_menu` y `type_block` se usan como **segmentos de ruta**. Si alguno contiene `../`
o separadores, la inclusión escapa del directorio previsto (**path traversal → LFI**).
El valor procede de la BD, así que requiere un administrador comprometido o un valor
contaminado — pero son páginas **públicas**, de modo que la ejecución la dispara
cualquier visitante.

### 13.2 La solución

Se sanea el segmento y se comprueba la existencia del archivo antes de incluirlo:

```php
$blockTpl = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) ($block['type_block'] ?? ''));

if ($blockTpl !== '' && is_file(dirname(__DIR__) . '/blocks/' . $blockTpl . '/' . $blockTpl . '.php')) {
    require_once dirname(__DIR__) . '/blocks/' . $blockTpl . '/' . $blockTpl . '.php';
}
```

Además, la ruta pasa a ser **absoluta** (`dirname(__DIR__)`) en lugar de relativa al
directorio de trabajo, con lo que el guard y la inclusión siempre coinciden.

### 13.3 Regresión detectada y corregida durante el propio arreglo

Al añadir el guard se usó por error `is_file(__DIR__ . '/blocks/…')`. Como
`core/templates/blocks/` **no existe** (el directorio real es `core/blocks/`), la
condición era siempre falsa y **habría dejado de renderizarse TODOS los bloques** de
todas las páginas públicas. Se detectó al verificar la resolución real de rutas y se
corrigió a `dirname(__DIR__) . '/blocks/'`, comprobando después que `core/blocks/`
existe y que los bloques reales (`about`, `contact`, `gallery`, `slide`, `video`)
resuelven.

> **Moraleja para futuros cambios:** un `is_file()` que no use exactamente la misma base
> que el `include()` convierte un endurecimiento en una funcionalidad rota y silenciosa.

### 13.4 Hallazgo adicional: `require/` no existe

El `include 'require/' . $menuTpl . '.php'` de las plantillas apunta a un directorio
`require/` que **no existe en ninguna parte del repositorio** (se comprobó también
`build/`). Es código muerto: antes habría producido un aviso de "failed opening" en cada
render; ahora se omite en silencio. Se documenta en lugar de inventar una ruta.

### 13.5 Otras inclusiones dinámicas revisadas

| Archivo | Estado |
|---|---|
| `core/config/dbconnection.php` (`$langFile`) | ✔ seguro: `basename($lg)` + lista blanca `['es','en']` |
| `core/controller/Languages.php` | ✔ **verificado seguro**: `currentLanguage` sólo sale de `detectLanguage()`, que valida toda entrada con `isSupported()` → `in_array($lang, ['es','en','fr','de','pt','it'], true)` |
| `core/controller/PagePublic.php`, `PageSystem.php` (`safeInclude`) | ✔ usan lista blanca de rutas y validación de segmentos |
| `core/managers/view.php:82` | ✅ **corregido**: `$pfile` (columna `pages.path_file`, o sea la BD) se usaba **tal cual** en `require_once`. Ahora se rechaza si contiene `..`, `\0`, ruta absoluta, letra de unidad o esquema (`://`), o caracteres fuera de `[A-Za-z0-9_\-/.]`. Probado con 10 casos (traversal, `/etc/passwd`, `C:\…`, `php://filter`, …) |
| `core/managers/builder.php:120` | ✅ **endurecido**: el nombre viene de `scandir()`; se normaliza con `basename()` antes del `include` para que no pueda salir de `components/` |
| `core/application/installer/install.php` | ✔ rutas fijas construidas con `$source` |

### 13.6 Código muerto con operaciones destructivas (NO habilitar)

| Archivo | Problema |
|---|---|
| `core/managers/views/menu_order.php` | Hace `TRUNCATE TABLE menu` **sin autorización ni CSRF**, e `include 'db.php'` (inexistente) → hoy muere con error fatal antes del TRUNCATE. **Está sin referencias**: no debe "arreglarse" sin añadir antes autenticación, autorización y token CSRF, porque eso convertiría un endpoint inerte en uno destructivo y accesible. |
| `core/managers/views/menu_sort.php` | Mismo `include 'db.php'` irresoluble y uso de `$db` → fatal. Sin referencias. |
| `core/managers/views/editmenu.php` | Sin referencias (sustituido por `menu_builder.php`, que sí está registrado en `DashboardRoutes.php`). Usaba la columna inexistente `pages.pos` y **no tenía** la comprobación de autorización que sí tiene su sucesor. ✅ Corregida la columna a `position`. |
| `core/managers/testorder/menu.php` | Sin referencias. Usaba la columna inexistente `pages.post`. ✅ Corregida a `position`. |

---

## 14. CSRF: los handlers validaban pero los formularios no emitían el token

### 14.1 El fallo (funcional + de seguridad)

Una pasada anterior añadió validación CSRF a **más de 60 puntos** de los controladores:

```php
// core/controller/UsersClass.php (Login)
if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
    $_SESSION["ErrorMessage"] = "Token CSRF inválido";
    return;
}
```

…pero **ningún formulario de `core/pages/` emitía el campo** (la búsqueda de `csrf` en
ese directorio no devolvía ni una coincidencia). Consecuencia:

> **El inicio de sesión estaba completamente roto.** Cualquier intento devolvía
> «Token CSRF inválido». Lo mismo ocurría con registro, recuperación de contraseña/PIN,
> recuperación de usuario y el formulario de contacto (cuyos handlers
> `SendMailer`/`SendData` también validan).

Es un caso claro de protección añadida sólo en un lado: el validador existe, el emisor no.

### 14.2 La solución

Se emite el campo en los formularios POST de los flujos de autenticación y cuenta con
`PepiuoX\Security\Csrf::field()`, que usa **exactamente la misma clave de sesión**
(`csrf_token`) que `Utils`, `SessionManager` y `SecurityAccess`, así que es compatible con
todos los validadores ya existentes:

```php
<form method="post">
    <?= \PepiuoX\Security\Csrf::field() ?>
```

**47 archivos / 53 formularios** en `core/pages/**`, `core/blocks/**`, `core/users/**`
y `core/managers/{admin,adduser,users}/**`.

Verificado en ejecución: el token emitido es aceptado por
`Utils::verifyCSRFToken()` → `true` y `SessionManager::verifyCSRFToken()` → `true`;
un token falso → `false`.

### 14.3 Cobertura final y por qué `MyCRUD` queda excluido

En una segunda pasada se añadió el campo a **los 113 formularios POST restantes**
(`core/managers/**`, `core/file-manager/**`, `core/elements/**`, `core/templates/**`),
previa corrección de los bucles que habrían roto (ver abajo). Resultado:
**todos los formularios POST de `core/` emiten el token salvo los 4 que genera
`MyCRUD.php`** (ver más abajo; excluyendo las librerías de terceros).

Los 15 formularios **sin `method` explícito** no se tocaron: sin `method` un formulario
envía por **GET**, así que añadir un token CSRF lo expondría en la URL. Quedan listados
como pendientes de revisión (P19).

**Bucles corregidos antes de añadir el campo** (construyen `SET` recorriendo `$_POST`):

| Archivo | Bucles |
|---|---|
| `core/application/installer/install.php` | 2 (`Update`, `UpdateCode`) |
| `core/managers/views/mailers.php` | 1 |
| `core/managers/views/settings.php` | 2 (`Update`, `UpdateSecures`) |

`column_manager.php` y `table_manager.php` **no** necesitaban cambio: recorren `$_POST`
sólo para recopilar nombres y luego los filtran contra una lista blanca fija.

**Excluido: `core/controller/MyCRUD.php`.** Genera las etiquetas `<form …>` **dentro de
cadenas PHP** y, en un primer intento, el campo se insertó dentro de esas cadenas (el
navegador habría recibido el texto literal `<?php echo … ?>`). Sus formularios generados
se dejan sin token: ese CRUD tampoco valida CSRF, así que no hay flujo roto, pero debe
abordarse cuando se añada la validación.

> **Aviso para futuros cambios:** el mismo patrón (insertar código en una línea que
> contiene `<form …>` construido por concatenación de cadenas) rompió
> `MyCRUD.php` porque el `>` de `$this->` cerraba antes de tiempo una regex ingenua.
> Si se automatiza esta tarea otra vez, hay que comprobar con el **tokenizador de PHP**
> que el código insertado no ha caído dentro de una cadena.

### 14.4 Comparaciones en tiempo no constante

| Archivo | Código | Corregir a |
|---|---|---|
| `core/managers/users.php` | `if ($_POST['csrf_token'] !== $_SESSION['csrf_token'])` **sin comprobar el método** → en una petición GET `$_POST['csrf_token']` no existe, la comparación daba «distinto» y la página **moría en cada carga** con "Ataque CSRF detectado" | ✅ validación sólo en POST, con `Csrf::isValid()` (`hash_equals`) |
| `core/includes/functions.php` | `validate_csrf_token()` comparaba con `!==` (tiempo no constante) | ✅ `hash_equals()` + comprobación de tipo/vacío |

`Utils`, `SessionManager` y `SecurityAccess` **ya** usan `hash_equals()` correctamente.

---

## 15. Validación CSRF central de los paneles (`Csrf::guardPost()`)

### 15.1 El hueco

La emisión del token ya estaba completa (§14), pero **el CRUD del panel no comprobaba
nada**: cualquier página externa con un formulario podía forzar, en nombre del
administrador autenticado, la creación/edición/borrado de páginas, entradas, temas,
menús, usuarios… Lo mismo en el área de usuario.

### 15.2 Una sola comprobación en el despachador

En vez de editar ~47 archivos de vista (cada uno con sus `if (isset($_POST[...]))` y su
formato), la validación se aplica **una vez** al principio de cada despachador, antes de
incluir la vista:

| Despachador | Cobertura |
|---|---|
| `core/managers/dashboard.php` | todo el dashboard (`DashboardRoutes::ViewIncludes()`) |
| `core/users/userprofile.php` | área de usuario: `personalInfo`, `infodetails`, `views/*`, `controls/*` |
| `core/users/view.php` | entrada alternativa del área de usuario |

```php
\PepiuoX\Security\Csrf::guardPost();
```

### 15.3 Política implementada (y por qué no rompe los AJAX)

| Caso | Resultado |
|---|---|
| GET | permitido (no modifica estado) |
| POST normal sin token | **403** |
| POST normal con token válido | permitido |
| POST normal con token inválido | **403** |
| POST AJAX sin token | permitido |
| POST AJAX con token inválido | **403** |

La excepción AJAX es **segura por construcción**: jQuery añade automáticamente
`X-Requested-With: XMLHttpRequest` a sus peticiones, y un sitio externo **no puede**
establecer esa cabecera — una cabecera personalizada dispara un *preflight* CORS que el
atacante no puede superar. Un POST cross-site clásico (formulario externo) no lleva esa
cabecera, por lo que **sí** se valida. Si algún día un AJAX envía el token (campo o
cabecera `X-CSRF-Token`), también se valida.

Verificado en ejecución: `GET` continúa; `POST` sin token es rechazado y `exit()` corta
la ejecución; 6/6 combinaciones de la tabla se comportan como se indica.

> **Corrección a una afirmación previa de este documento:** una primera estimación dijo
> que había JS/AJAX en «46 de ~47 archivos de `core/managers/**`». Era **falso**: el
> patrón incluía `fetch(`, que casaba con el `->fetch()` de PDO. El recuento real de
> llamadas `$.ajax/.post/.get` es de **6 archivos**, y sólo `build.php` y `builder.php`
> construyen los datos a mano. Esa medición errónea fue la que hizo descartar el enfoque
> central en la ronda anterior.

---

## 16. Clases inalcanzables por autoload (nombre ≠ archivo)

Tres clases de `core/controller/` declaraban un nombre distinto al de su archivo, por lo
que el autoloader (que busca `<Clase>.php`) **no podía cargarlas**:

| Archivo | Declara | ¿Uso externo? |
|---|---|---|
| `SecurityAccessClass.php` | `SecurityAccess` | no (sólo se declara) |
| `SetSession.php` | `SessionClass` | no (sólo se autorreferencia) |
| `statsPages.php` | `RemoteAddress` **y** `PageStatsRecorder` | no (uso interno del propio archivo) |

Al usarse sólo dentro de su propio archivo funcionaban, así que el fallo era **latente**.
Se han añadido al `COMPAT_MAP` de `core/Autoload.php` para que también sean cargables por
nombre. Verificado: `SessionClass` y `RemoteAddress` resuelven a su archivo real.

---

## 17. Segunda pasada (endurecimiento y bugs de coherencia)

### 17.1 Sesión: dos bugs acoplados (crítico)

La configuración "segura" de sesión estaba repartida en tres controladores y **nunca se
aplicaba**, porque el bootstrap (`loader.php` → `dbconnection.php`) llamaba a
`session_start()` antes:

- `SecurityAccessClass::configureSession()` no hacía nada (su guarda
  `session_status() === PHP_SESSION_NONE` era falsa).
- `Session::startSession()` y `SessionManager::configureSession()` llamaban a
  `session_set_cookie_params()` con la sesión ya activa → aviso y sin efecto.
- Por tanto **`httponly`, `samesite`, `use_strict_mode` y `use_only_cookies` no llegaban
  a aplicarse** en ejecución.

Y los tres forzaban `secure = true` sin condiciones, lo que **impide iniciar sesión por
HTTP** (el navegador no envía la cookie) — y este proyecto está configurado en HTTP.

**Solución:** `PepiuoX\Security\SessionConfig` (`core/src/Security/SessionConfig.php`),
aplicada en el bootstrap **antes** de `session_start()`, con:

- `use_strict_mode`, `use_only_cookies`, `use_trans_sid=0`, `cookie_httponly`,
  `cookie_samesite=Lax`, `cookie_path=/`;
- `cookie_secure` **sólo si la petición es HTTPS** (no se confía en `X-Forwarded-Proto`,
  que controla el cliente).

Los tres controladores ahora delegan en ella. `Lax` (en vez de `Strict`) sigue bloqueando
los POST cross-site —que es lo relevante para CSRF— sin romper la navegación desde enlaces
externos. Además, `Session.php` al re-crear la cookie de sesión con 30 días de caducidad
usaba la firma posicional de `setcookie()`, que **descarta el atributo `SameSite`**.

### 17.2 Inyección SQL por nombre de tabla: `page` no existe (10 consultas)

El esquema (`installer/sql/db.sql`, línea 2442) define **`pages`** (plural) y **no existe
ninguna tabla `page`**. Sin embargo 10 consultas usaban `page`, por lo que lanzaban
`PDOException ("Table ... doesn't exist")`:

`core/blocks/content/content.php`, `core/blocks/simple/simple.php`, `core/blocks/video/video.php`,
`core/managers/app.php` (incluido el calificador del `ON`), `core/managers/update.php` (×2),
`core/managers/updatecontent.php`, `core/managers/testorder/menu.php`,
`core/managers/views/editmenu.php`, `core/managers/views/pages.php` (×2).

Se conservaron como **alias legítimos** (no son la tabla):
`core/managers/views/Videos.php`, `core/managers/views/Galleries.php` y
`core/managers/views/Gallery.php`, que hacen
``LEFT JOIN (SELECT … FROM pages) `page` ON … = page.id``,
y la columna `pageviews.page`.

### 17.3 Otras correcciones de esta pasada

| Archivo | Defecto | Corrección |
|---|---|---|
| `core/application/installer/install.php` | `RandKey()` generaba el **SECURE_TOKEN del sitio** con `rand()` (predecible) | `random_int()`; `RandHash()` usa `random_bytes()` |
| `core/application/installer/installUser.php` | El **PIN del administrador** y los códigos de verificación usaban `rand()`/`mt_rand()` | `random_int()` |
| `core/manager*/session.php` | `mt_rand()` para códigos de verificación | `random_int()` |
| `core/managers/views/theme_template.php` | Alta de tema rota por 3 motivos: `uniqid(rand())` insertado en `theme_id` (INT AUTO_INCREMENT), columna inexistente `theme` (es `theme_bootstrap`) y `rand()` predecible | `AUTO_INCREMENT` + `lastInsertId()`; columna `theme_bootstrap`; sin `rand()` |
| `core/managers/views/blog_posts.php` | `INSERT` con **12 marcadores para 10 columnas** → *Invalid parameter number* | 10 marcadores |
| `core/managers/views/blog_posts.php` | `UPDATE` con comillas sueltas (`title=?'`, `menu='`) → SQL inválido | comillas eliminadas |
| `core/managers/views/Profiles.php` | `VALUES( , …)` (valor vacío) y `:description` inexistente en el formulario | 8 columnas ↔ 8 parámetros, usando `description_en`/`description_es` como hace la rama de edición |
| `core/managers/build.php` | `require_once 'tu_archivo_de_conexion_pdo.php'` (**marcador sin completar**) y `$pdo` inexistente → fatal | bootstrap canónico + `$conn` |
| `core/managers/builder.php` | `Savedata()` usaba `$this->conn` fuera del ámbito de objeto | `global $conn` |
| `core/elements/sidenav.php` | `$poste['category_id']` (la variable del bucle es `$post`) → la categoría nunca se consultaba | `$post['category_id']` |
| `core/controller/DatabaseCacheDriver.php` | `unserialize()` sobre datos de BD → *object injection* | `['allowed_classes' => false]` |
| `core/index.php`, `core/application/installer/index.php` | `header('Location: http://' . $_SERVER['HTTP_HOST'])` → inyección de host / open redirect | `header('Location: /')` |
| `core/includes/functions.php` | `redirect($url)` sin validar: permitía CRLF (inyección de cabeceras) y esquemas `javascript:`/`data:` | saneado de CRLF y lista negra de esquemas |

### 17.4 Evidencia de verificación (ronda 2)

| Prueba | Resultado |
|---|---|
| `php -l` sobre los **559** `.php` de `core/` | **559 OK, 0 errores** |
| Peticiones SQL con nombre de tabla `page` (inexistente) | **0** (los 3 `page.` restantes son alias de subconsulta legítimos) |
| `extract($_POST/$_FILES)` sin `EXTR_SKIP` | **0** (los 3 restantes son comentarios) |
| `rand()`/`mt_rand()` reales | **0** (el resto eran comentarios ya corregidos) |
| `LIMIT` ligado con `execute([...])` | **0** |
| `unserialize()` sin `allowed_classes` | **0** |
| Archivos PHP con BOM UTF-8 (rompe cabeceras/sesiones) | **0** |
| Autoload de las 11 clases clave (namespaced + legadas + alias `Database`) | **11/11 OK** |
| `SessionConfig::apply()` antes de cualquier salida | `httponly=1`, `samesite=Lax`, `use_strict_mode=1`, `use_only_cookies=1`, `secure=0` sin HTTPS ✔ |
| `SqlIdentifier::quoteDatabase('mi-cms')` / rechazo de `` a`b `` | `` `mi-cms` `` / excepción ✔ |

---

## 18. Validación contra el esquema REAL (MariaDB)

**Hallazgo metodológico importante:** en este entorno **sí hay un servidor MariaDB en
marcha** (base `phpgrapesjs`, puerto 3306). Por tanto, además del análisis estático, el SQL
se puede **validar contra el servidor sin ejecutarlo**:

```php
$conn->prepare($sql);   // con ATTR_EMULATE_PREPARES => false el SERVIDOR valida
                        // tablas y columnas, y lanza PDOException si algo no existe
```

Esa comprobación detectó **tres defectos que el análisis estático no puede ver**: el SQL
era sintácticamente correcto pero **las columnas no existían**.

### 18.1 Defectos encontrados por el esquema real (y corregidos)

| Archivo | SQL anterior | Error real del servidor | Corrección |
|---|---|---|---|
| `core/users/views/info.php` | `... created = ?, updated = ?` | `Unknown column 'created'` — `users_info` usa `created_at`/`updated_at` | `created_at` / `updated_at` |
| `core/users/views/privacy.php` | `... WHERE idPri = ?` y `created`/`updated` | `Unknown column 'idPri'` — la PK de `users_privacy` es **`idUsr`**; las fechas son `created_at`/`updated_at` | `idUsr`, `created_at`, `updated_at` |
| `core/users/views/profile.php` | `... created = ?, updated = ?` | `Unknown column 'created'` — `users_profiles` usa `created_at`/`updated_at` | `created_at` / `updated_at` |

### 18.2 Confirmaciones empíricas de correcciones anteriores

| Afirmación previa | Comprobación contra el servidor |
|---|---|
| Las consultas con tabla `page` estaban rotas | `pages` **existe** (6 filas); `page` **no existe** → confirma las 10 correcciones de §17.2 |
| El alta de temas insertaba una cadena en un PK entero | `themes.theme_id` es `int(11) auto_increment` → confirma el fix |
| La columna del tema es `theme_bootstrap` | `theme_bootstrap` existe; `theme` no → confirma el fix |
| La columna de orden del menú es `position` | `pages.position` existe; `pos` no → confirma P14 |
| Los identificadores de fila resueltos desde la sesión | `users_info.userid`, `users_locations.usercode`, `users_verifications.usercode`, `users_profiles.idp` existen todas |
| Las sentencias SQL corregidas | **14/14 `prepare()` válidos** contra MariaDB 12.3.3 |

### 18.3 Tablas que NO existen en el esquema (funciones sin soporte)

| Tabla | Referenciada por | Situación |
|---|---|---|
| `my_info` | `core/blocks/about/about.php`, `core/blocks/profile/profile.php`, `core/managers/views/Profiles.php` | **No está ni en `installer/sql/db.sql` ni en la base de datos.** Sus columnas (`idPro`, `first_name`, `last_name`, `age`, `gender`, `description_en/es`, `image`, `active`) **duplican** las de `users_info` (`userid`, `firstname`, `lastname`, `age`, `gender`, …): parece una tabla legacy sustituida por `users_info`. Migrar esas consultas **es una decisión de producto**, así que no se han reescrito a ciegas. |
| `media` | `core/controller/MediaManager.php` | No existe ni en la base ni en el esquema: su `INSERT INTO media …` fallará hasta que se cree la tabla (el SQL ya está parametrizado y es correcto). |

### 18.4 Correcciones adicionales de esta ronda

| Archivo | Defecto | Corrección |
|---|---|---|
| `core/managers/dashboard.php`, `core/users/userprofile.php`, `core/users/view.php` | El CRUD del panel y el área de usuario **no validaban CSRF** (ver §15) | `Csrf::guardPost()` central |
| `core/managers/view.php` | `pages.path_file` (BD) usado tal cual en `require_once` → LFI | validación de ruta §13.5 |
| `core/managers/builder.php` | nombre de `scandir()` usado en `include` | `basename()` |
| `core/managers/views/editmenu.php`, `core/managers/testorder/menu.php` | columnas inexistentes `pages.pos` / `pages.post` | `position` |
| `core/src/Security/SessionConfig.php` | `start()` llamaba a `session_start()` aunque ya se hubieran enviado cabeceras (aviso) | guarda `headers_sent()` |

> **Recomendación para futuras rondas:** aprovechar que hay servidor de base de datos para
> validar con `prepare()` **cada** sentencia SQL que se toque. Es la única comprobación que
> detecta columnas inexistentes, y en esta ronda aportó tres bugs reales.

---

## 19. Barrido completo del SQL contra el esquema real

Se ejecutó un barrido automático que extrae **todos los literales SQL** de `core/`
(excluyendo las librerías de terceros) y los pasa por `prepare()` contra MariaDB. Sólo se
reportan los errores accionables: **1054** (columna inexistente) y **1146** (tabla
inexistente). Los 1064 (sintaxis) se descartan porque suelen ser fragmentos concatenados.

### 19.1 Columnas inexistentes → corregidas en esta ronda

| Archivo | SQL anterior | Columna real | Corrección |
|---|---|---|---|
| `core/controller/ColumnSettings.php:46` | `WHERE name_table = :tname` | `table_name` | ✅ |
| `core/controller/UsersVerify.php:121` | `SET … banned=:b` | `is_banned` | ✅ |
| `core/controller/UsersForgot.php:307`, `UserForgot.php:235` | `FROM forgot_pass WHERE mkhash = ? AND password_key = ?` | `forgot_pass` no tiene `mkhash`; se identifica por `password_key` | ✅ |
| `core/controller/UsersForgot.php:434`, `UserForgot.php:367` | `FROM forgot_pin WHERE mkhash = ? AND password_key = ?` | `forgot_pin` se identifica por **`pin_key`** | ✅ |

Las sentencias corregidas se revalidaron contra el servidor: **4/4 `prepare()` correctos**.

### 19.2 Columnas inexistentes → REQUIEREN DECISIÓN

| Archivo | Columna | Por qué |
|---|---|---|
| `core/controller/UsersUpdates.php:142` | `email_verified` | Consulta `users_profiles … WHERE … email_verified = :ev`, pero esa columna está en **`users`**, no en `users_profiles`. Hay que decidir si se hace `JOIN` con `users` o si se elimina la condición. |
| `core/includes/functions.php:1194` y `:1216` | `recovery_token`, `recovery_expires` | **No existen en ninguna tabla** (`users` tiene `mkpin`, `email_verified`, …). La recuperación por token necesita **añadir esas columnas** al esquema. |
| `core/managers/users/dashboard.php:13` y `:25` | `is_active`, `is_banned`, `level` | Consulta `FROM users`, pero esas columnas están en **`uverify`** (`is_activate`, `is_banned`, `level`). Mezcla de tablas: hay que decidir cuál es la correcta o hacer `JOIN`. |
| `core/managers/users/stats-api.php:62` | `role` | No existe: el equivalente en `uverify` es **`level`**. |

> `core/controller/db.php:10` aparece en el barrido pero es un **falso positivo**: es un
> ejemplo dentro del docblock de la clase.

### 19.3 Tablas inexistentes (hallazgo estructural)

Estas tablas **no están ni en `core/application/installer/sql/db.sql`** (113 `CREATE TABLE`)
**ni en la base de datos `phpgrapesjs`** (115 tablas), y tampoco aparecen completas en
ninguna otra base del servidor (`volunteer_platform` sólo tiene `galleries`):

| Tabla | Archivos que la usan | Notas |
|---|---|---|
| **`galleries`** | **26** (toda la familia `core/blocks/galleries*`, `gallery*`, `homegal*`, `slide*`, `prints`, `single`…) | el subsistema de galerías completo |
| `press_gal` | 3 (`blocks/press`, `blocks/pressgallery`) | notas de prensa |
| `files` | 2 (`includes/create-share-token.php`, `toggle-public.php`) | gestor de archivos compartidos |
| `my_info` | 2 (`blocks/about`, `managers/views/Profiles.php`) | duplica columnas de `users_info` |
| `videos` | 1 (`blocks/video`) | bloque de vídeo |
| `type_blocks` | 1 (`managers/views/TypeBlocks.php`) | **además lo usa el renderizador público** (`blocks`/`type_blocks` en las plantillas) |
| `change_pass`, `change_pin` | 1 (`UsersChange.php`) | |
| `domains` | 1 (`multiDomains.php`) | |
| `people` | 1 (`BlogPost.php`) | |
| `profiles` | 1 (`users/personalInfox.php`) | |
| `productos_favoritos`, `rating_servicio` | 2 (`Rating.php`, `AddCart.php`) | módulo de tienda |

**Consecuencia:** los bloques públicos de galería/vídeo/prensa y sus gestores del panel
**no pueden funcionar** en esta instalación: lanzan `PDOException` (tabla inexistente).
No es un fallo introducido por esta migración, sino un **hueco de esquema previo**: el
instalador nunca incluyó esas tablas.

**Qué haría falta para resolverlo:** diseñar y añadir esas tablas al esquema (columnas,
claves y relaciones), lo que es una **decisión de producto/datos** — no se ha inventado
un modelo de datos a ciegas. Como referencia, el propio código define el contrato: cada
`SELECT/INSERT/UPDATE` de esos bloques enumera las columnas que espera.

> `image_gal` y `multimedia_gal` no aparecen en el barrido porque el error se produce antes,
> al consultar `galleries`; pero las usan las mismas 26 plantillas.

### 19.4 Conclusión de cobertura

| Comprobación | Resultado |
|---|---|
| Literales SQL validados con `prepare()` contra MariaDB | **~200**, de los cuales 55 con error accionable |
| Columnas inexistentes corregidas | 4 sentencias (6 sitios) |
| Columnas inexistentes pendientes de decisión | 4 (todas por mezcla de tablas o columnas que no existen en ninguna) |
| Tablas inexistentes | **13** (una de ellas, `galleries`, usada por 26 archivos) |
