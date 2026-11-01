# Migración MySQLi → PDO (carpeta `core`)

> **DOCUMENTO DE REFERENCIA PERMANENTE.**
> Consúltalo antes de tocar cualquier archivo de `core/` que hable con la base de datos.
> Si agregas o mueves archivos, **actualiza el inventario (§6)** en el mismo commit.

| | |
|---|---|
| **Ámbito** | Sólo `core/` (donde vive el sistema). |
| **Fecha de la migración** | Ver §8 Historial. |
| **Archivos PHP modificados** | **125** |
| **Resultado** | El sistema propio de `core/` queda 100 % sobre **PDO (`pdo_mysql`)**. Sin `mysqli`, sin `new mysqli`, sin `MYSQLI_*`. |
| **Verificación** | `php -l` sobre los 554 archivos `.php` de `core/`: 553 OK, 1 fallo **previo y ajeno** a esta migración (`core/controller/test1.php`, ver §7.1). Escaneo residual: limpio. |

---

## 1. Arquitectura de conexión (capa canónica)

Toda la aplicación obtiene su conexión de **un solo lugar**:

```
core/config/server.php        credenciales / conexiones (array $settings)
core/config/Database.php      class Database  -> conecta PDO (singleton) + helpers
core/config/dbconnection.php  bootstrap:  $link = new Database();  $conn = $link->getConnection();
```

| Variable / clase | Tipo real | Notas |
|---|---|---|
| `$conn` (global) | **`PDO`** | Objeto único del sistema. **NO es `mysqli`.** |
| `$link` | `Database` | Helpers `select()`, `execute()`, `lastInsertId()`, transacciones… |
| `$this->conn` en `core/controller/*` | **`PDO`** | Inyectado por constructor. **Conservar.** |
| `$this->connection` en `core/controller/db.php` | `PDO` | Wrapper `db` con API fluent. |

### Regla de contexto (importante)

`$this->conn` **sólo es válido dentro de una clase** (`core/controller/*`).
En archivos de **vista / include** (`core/blocks/`, `core/managers/views/`, `core/users/`, `core/elements/`, `core/templates/`)
se usa **`$conn`** con `global $conn;`, porque se incluyen desde scripts procedurales donde `$this` no existe:

```php
global $conn;                 // en alcance de archivo (no-op inofensivo) o dentro de la función
$stmt = $conn->prepare($sql);
```

### Atributos de conexión ya configurados

```php
PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // los errores LANZAN excepción
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // $row['columna'] funciona directo
PDO::ATTR_EMULATE_PREPARES   => false,                   // prepares nativos
PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
```

Consecuencias prácticas:

1. **`$conn->error` no existe.** → `$conn->errorInfo()[2]` (o mejor, capturar `PDOException`).
2. **`or die(...)` es código muerto.** Si la consulta falla, PDO lanza `PDOException`.
3. **`$row['columna']` funciona por defecto** (fetch mode `FETCH_ASSOC`).
4. **`rowCount()` sobre `SELECT`** funciona: PDO_MySQL usa *buffered queries* por defecto.
5. **`PDO::query()` devuelve `PDOStatement`, nunca `true`.** Por eso `=== true` **siempre era falso** y se cambió a `!== false`.

### Helpers de `Database` (`core/config/Database.php`)

```php
$link->getConnection(): PDO
$link->select(string $sql, array $params = []): array
$link->execute(string $sql, array $params = []): int
$link->lastInsertId(): string
$link->beginTransaction() / commit() / rollBack()
$link->testConnection(): bool
$link->getDatabaseInfo(): array
$link->getPageContent(int $id): array|string
```

---

## 2. Reglas de conversión (obligatorias)

### 2.1 Lectura de resultados

| MySQLi (prohibido) | PDO (correcto) | Cuándo |
|---|---|---|
| `$s->fetch_array()` | `$s->fetch(PDO::FETCH_BOTH)` | si el código usa índices numéricos `$row[0]` |
| `$s->fetch_array()` | `$s->fetch(PDO::FETCH_ASSOC)` | uso normal (**preferido**) |
| `$s->fetch_assoc()` | `$s->fetch(PDO::FETCH_ASSOC)` | siempre |
| `$s->fetch_row()` | `$s->fetch(PDO::FETCH_NUM)` | siempre |
| `$s->fetch_object()` | `$s->fetch(PDO::FETCH_OBJ)` | siempre |
| `mysqli_fetch_array($s)` | `$s->fetch(PDO::FETCH_BOTH)` | — |
| `mysqli_fetch_assoc($s)` | `$s->fetch(PDO::FETCH_ASSOC)` | — |
| `mysqli_fetch_row($s)` | `$s->fetch(PDO::FETCH_NUM)` | — |
| `mysqli_num_rows($s)` | `$s->rowCount()` | conteo de filas |
| `$s->num_rows` | `$s->rowCount()` | idem |
| `$s->fetchAll()` | `$s->fetchAll(PDO::FETCH_ASSOC)` | traer todo |

### 2.2 `get_result()` — NO existe en PDO

```php
// ❌ MySQLi
$stmt = $conn->prepare("SELECT * FROM t WHERE id = ?");
$stmt->bind_param("s", $id); $stmt->execute();
$res = $stmt->get_result(); $stmt->close();
$row = $res->fetch_assoc();

// ✅ PDO
$stmt = $conn->prepare("SELECT * FROM t WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();
```

### 2.3 Parámetros vinculados

| MySQLi (prohibido) | PDO (correcto) |
|---|---|
| `$stmt->bind_param("ss", $a, $b)` | `$stmt->execute([$a, $b])` |
| `$stmt->bind_param("i", $n)` | `$stmt->execute([$n])` o `bindValue(':n', $n, PDO::PARAM_INT)` |
| — (no existía) | `bindValue(':lim', (int)$lim, PDO::PARAM_INT)` para `LIMIT ?` |

- **`LIMIT ?` exige `bindValue(..., PDO::PARAM_INT)`** (con `EMULATE_PREPARES => false` el driver rechaza strings en `LIMIT`).
- Los placeholders posicionales `?` se conservaron donde ya existían. Código nuevo: usar `:nombre`.

### 2.4 Cierre de statements

| MySQLi | PDO |
|---|---|
| `$stmt->close()` | `$stmt->closeCursor()` |
| `$conn->close()` | **se elimina** (o `$conn = null;` sólo en conexiones temporales) |

- `PDOStatement` **no tiene `close()`**. `closeCursor()` libera el cursor.
- **`closeCursor()` debe ir DESPUÉS de leer** los datos. `fetch()`/`rowCount()` tras `closeCursor()` es inválido.
- La conexión global `$conn` es un **singleton compartido**: no se cierra a mano.
  (Única excepción: las conexiones temporales del instalador, §4.)

### 2.5 Errores

| MySQLi | PDO |
|---|---|
| `mysqli_error($conn)` / `mysqli_error()` | `$conn->errorInfo()[2]` |
| `$conn->error` | `$conn->errorInfo()[2]` |
| `$stmt->error` | `$stmt->errorInfo()[2]` |
| `$conn->query($sql) or die($conn->error)` | `$conn->query($sql);` + `try/catch (PDOException)` |
| `if ($conn->query($sql) === TRUE)` | `if ($conn->query($sql) !== false)` |
| `$conn->connect_error` / `connect_errno` | no existen: la conexión **lanza** en el bootstrap |

Guard coherente (patrón ya usado en el proyecto, p. ej. `core/controller/MyCRUD.php`):

```php
if (!($conn instanceof PDO)) {
    throw new RuntimeException('...requires $conn to be a PDO instance.');
}
```

### 2.6 Escapado de strings

| MySQLi | PDO |
|---|---|
| `mysqli_real_escape_string($conn, $str)` | **eliminado** |

En `core/config/function.php`, `protect()` conserva `trim`, `stripslashes`, `htmlentities`, `htmlspecialchars`
(saneado de **salida HTML**) y ya **no** llama a mysqli.

> ⚠️ `protect()` **no** sustituye a los prepared statements. Nunca lo uses como única defensa.

### 2.7 Idiomas MySQLi detectados y su equivalente exacto

Estos no estaban en el plan inicial; aparecieron durante la migración y **también son MySQLi-only**:

| MySQLi | PDO |
|---|---|
| `$stmt->affected_rows` | `$stmt->rowCount()` |
| `$conn->affected_rows` (sin handle) | asignar la query a `$stmt` y usar `$stmt->rowCount()` |
| `$conn->insert_id` | `$conn->lastInsertId()` |
| `$result->fetch_fields()` | `getColumnMeta($i)` **o** `array_keys($row)` (ver §7.4) |
| `$result->field_count` | `$result->columnCount()` |
| `$result->fetch_field()` | `(object) $result->getColumnMeta($i)` |
| `mysqli_data_seek($r, $i)` | `fetchAll()` y recorrer, o `foreach` |
| `while (list($k,$v) = each($a))` | `foreach ($a as $k => $v)` — **`each()` fue eliminado en PHP 8** |
| `$conn->query($sql) or trigger_error($conn->error)` | eliminar; PDO lanza |

### 2.8 Lo que NO se debe tocar en una migración

- El texto SQL (tablas, columnas, `ORDER BY`, `LIMIT`, joins).
- La estructura HTML/PHP de las plantillas y el orden de las sentencias.
- Nombres de variables y funciones existentes.
- La lógica de negocio y los `$_POST`/`$_GET` existentes.

---

## 3. Guía rápida (cheat-sheet) para código nuevo

```php
// ✅ Varias filas
$stmt = $conn->prepare("SELECT id, title FROM pages WHERE active = ? ORDER BY id");
$stmt->execute([$active]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Una fila
$stmt = $conn->prepare("SELECT * FROM galleries WHERE idGal = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Insertar y obtener el ID
$stmt = $conn->prepare("INSERT INTO pages (title, slug) VALUES (?, ?)");
$stmt->execute([$title, $slug]);
$newId = $conn->lastInsertId();

// ✅ Existencia / conteo
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$stmt->execute([$email]);
$total = (int) $stmt->fetchColumn();

// ✅ Paginación (obligatorio PARAM_INT)
$stmt = $conn->prepare("SELECT * FROM pages ORDER BY id LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', (int) $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', (int) $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Transacción
try {
    $conn->beginTransaction();
    // ... varias consultas ...
    $conn->commit();
} catch (PDOException $e) {
    $conn->rollBack();
    error_log($e->getMessage());
}
```

### Prohibido

`mysqli_*`, `new mysqli`, `MYSQLI_*`, `->fetch_array(`, `->fetch_assoc(`, `->fetch_row(`,
`->fetch_object(`, `->num_rows`, `->get_result(`, `->bind_param(`, `->real_escape_string(`,
`->affected_rows`, `->insert_id`, `->fetch_field(`, `->field_count`, `->connect_error`,
`$conn->error`, `$conn->close()`, `$stmt->close()`, `MysqliConnection()`.

### Escaneo de control

```powershell
Get-ChildItem core -Recurse -File -Filter *.php |
  Where-Object { $_.FullName -notmatch '\\filemanager\\php\\' -and $_.FullName -notmatch '\\PHPMailer\\' } |
  Select-String -Pattern 'mysqli_|MYSQLI_|->fetch_array\(|->fetch_assoc\(|->fetch_row\(|->fetch_object\(|->num_rows|->get_result\(|->bind_param\(|real_escape_string|->affected_rows|->insert_id|->fetch_field\(|->field_count|->connect_error|\$conn->error\b|\$conn->close\(\)|\$stmt->close\(\)'
```

Resultado esperado: **sin coincidencias** (salvo comentarios explicativos).

---

## 4. Conexiones temporales: el instalador

`core/application/installer/` se ejecuta **antes** de que exista `core/config/server.php`,
así que no puede usar el singleton `$conn`. Crea sus propias conexiones PDO mediante un helper local:

```php
// core/application/installer/install.php
function installerPdo(string $host, string $user, string $password, string $dbname = ''): PDO
{
    $dsn = "mysql:host={$host};charset=utf8mb4";
    if ($dbname !== '') {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    }
    return new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
```

- **Sin `dbname`** → permite `CREATE DATABASE`.
- `$conn = null;` sustituye a `$conn->close()` en estas conexiones temporales.

---

## 5. Archivos EXCLUIDOS (y por qué)

| Ruta | Motivo | Acción |
|---|---|---|
| `core/filemanager/php/elFinder*` (incl. `elFinderVolumeMySQL.class.php`) | Librería de terceros **elFinder** (vendorizada). Tiene su propio driver MySQLi y su propio ciclo de vida. | **No migrar.** No usa la conexión PDO del sistema. |
| `core/PHPMailer/**` | Librería de terceros. **No accede a base de datos** (sus `->close()` / `->error` son de SMTP: falsos positivos del escaneo). | **No migrar.** |
| `core/pages/gallery.php` | Aplicación de terceros **files.photo.gallery** (vendorizada). Su único match era `ZipArchive::close()`. | **No migrar.** |

> Para reemplazar el driver de elFinder hay que **actualizar la librería completa**, no editar el archivo vendorizado.

---

## 6. Inventario de archivos migrados (125)

### 6.1 `core/config/` y `core/application/installer/` — núcleo e instalación

| Archivo | Funcionalidad | Cambios aplicados |
|---|---|---|
| `core/config/function.php` | Helpers globales: saneado, constructores de `<select>` de páginas/menús, lectura de `ENUM`. | `protect()`: eliminado `mysqli_real_escape_string`. `nparent()`: `num_rows`→`rowCount()`, `fetch_array`→`fetch(ASSOC)`. `vwparent()`: `fetch_assoc`→`fetch(ASSOC)`. `sparent()`, `pparent()`, `slmenu()`, `menuopt()`: `fetch_array`→`fetch(ASSOC)`. `enum_values()`: `fetch_array(MYSQLI_ASSOC)`→`fetch(ASSOC)`. `selType()`, `enumsel()`: `fetch_array`→`fetch(ASSOC)`. |
| `core/application/installer/install.php` | Instalador: pasos 1-6 (comprobar BD, crearla, importar `sql/db.sql`, definir configuración, crear usuario admin, **generar `server.php` y `dbconnection.php`**). | Añadido helper `installerPdo()`. Sustituidos 4 `new mysqli(...)` por `installerPdo()`. `connect_error`→`try/catch (PDOException)`. `=== true`→`!== false`. `$conn->error`→`$e->getMessage()`. `fetch_fields()`→`getColumnMeta()`; `fetch_assoc()`→`fetch(ASSOC)`. `$conn->close()`→`$conn = null;` (5 sitios). **Crítico:** el archivo `dbconnection.php` que genera ahora emite `$link->getConnection()` en lugar del inexistente `$link->MysqliConnection()`. |
| `core/application/installer/installUser.php` | Crea el usuario «Super Admin» en el paso 5 (valida usuario/email, inserta en `uverify`, `users`, `users_profiles`, `users_info`, `users_actions`). | 4 `bind_param()`→`execute([...])`; `get_result()+fetch_assoc()`→`fetch(ASSOC)` directo; `num_rows`→`rowCount()`; `affected_rows`→`rowCount()`; `close()`→`closeCursor()`; `$this->connection->close()` eliminado. |

### 6.2 `core/managers/` — backend del panel (19 top-level + 29 vistas)

**Top-level**

| Archivo | Funcionalidad | Cambios |
|---|---|---|
| `app.php` | Endpoint JSON CRUD del menú (`?action=read/create/update/delete`). | `include "db.php"` (inexistente)→bootstrap canónico guardado. `fetch_assoc`→`fetch(ASSOC)`. Eliminado `$conn->close()`. |
| `builder.php` | Constructor GrapesJS: carga/guarda `html_content`/`css_content` de la página. | `bind_param`+`get_result`+`fetch_assoc`→`execute([...])`+`fetch(ASSOC)` (2 bloques). `affected_rows`→`rowCount()`. `close()`→`closeCursor()`. |
| `pagination.php` | Demo de paginación (`students`). | Usaba `$mysqli` **inexistente**→`$conn`. `LIMIT ?,?`→`bindValue(PARAM_INT)`. `fetch_assoc`→`fetch(ASSOC)`. |
| `qtmp.php` | Añade la fila de configuración de la tabla `templates`. | `num_rows`→`rowCount()`. `=== TRUE`→`!== false`. `$conn->error`→`errorInfo()[2]`. |
| `save.php` | Guarda contenido/estilo de una página. | `bind_param`+`affected_rows`+`close()`→`execute([...])`+`rowCount()`+`closeCursor()`. |
| `savecontent.php` | Crea página + entrada de menú. | `=== TRUE`→`!== false` (2). `$conn->insert_id`→`lastInsertId()`. |
| `session.php` | Sistema de sesiones legado (Jpmaster77). | `$this->connection` fuera de objeto→`global $conn` + `fetch(ASSOC)`. |
| `sitemap.php` | Genera `sitemap.xml`. | `num_rows`→`rowCount()`. `fetch_object`→`fetch(FETCH_OBJ)`. |
| `social.php` | Formulario de redes sociales. | `or trigger_error($conn->error)` eliminado. `fetch_array`→`fetch(FETCH_BOTH)` (conserva claves numéricas y asociativas). |
| `tbn.php` | AJAX: comprueba si el nombre de columna ya existe. | `mysqli_error()`→guard PDO. `fetch_field()`→`getColumnMeta()`. |
| `tbq.php` | AJAX: construye `<select>` de columnas relacionadas (`selrQuery`/`selvQuery`). | Igual que `tbn.php` (2 funciones, 2 guards). |
| `tt.php` | Demo de consulta de `table_queries`. | `$link->query()` (inexistente en `Database`)→`$conn`. `mysqli_num_rows`→`rowCount()`. `fetch_assoc`→`fetch(ASSOC)`. |
| `update_order.php` | AJAX: reordena `items`. | `include("connect.php")` (inexistente)→bootstrap canónico. `mysqli_query(...) or die(mysqli_error())`→`$conn->query()` + comentario. |
| `updatetmp.php` | AJAX: actualiza una fila de `menu`. | `bind_param`+`close()`→`execute([...])`+`closeCursor()`. |
| `view_active.php` | Lista de usuarios activos (`uverify`). | `num_rows`→`count(fetchAll())`. `mysqli_data_seek`+`mysqli_fetch_row` en bucle→`foreach` sobre `fetchAll(FETCH_NUM)`. |
| `vuejs_crud.php` | Generador de CRUD Vue + API PHP; también gestiona `table_config`. | 16 conversiones: `num_rows`→`rowCount()`, `fetch_assoc/array`→`fetch(ASSOC)`, `field_count`+`fetch_field()`→`columnCount()`+`getColumnMeta()`, `mysqli_num_rows`/`mysqli_fetch_assoc`→PDO, `$conn->close()` eliminado. **También se migró el código PHP que genera por concatenación** (`$apicontent`) para que el archivo emitido sea PDO. |
| `testorder/menu.php` | Menú legado `$database`. | `$database` (inexistente)→`global $conn`; `fetch_array`→`fetch(ASSOC)`. |
| `testorder/menu_list.php` | Lista ordenable de `menu`. | `$database`→`$conn`; `or die($database->error)` eliminado; `fetch_array`→`fetch(ASSOC)`. |
| `testorder/sort_menu.php` | AJAX: guarda el orden del menú. | `$database`→`$conn`; `or die` eliminado; bootstrap guardado. |

**`core/managers/views/` (29)**

Todas las vistas del panel. La conversión es la misma familia; se detalla lo relevante y lo singular.

| Archivo | Funcionalidad | Cambios |
|---|---|---|
| `Blocks.php` | CRUD de bloques (`blocks`). | `fetch_array`→`fetch(ASSOC)`, `num_rows`→`rowCount()`, `each()`→`foreach` (2), `$this->conn`→`global $conn`, `or die(...)` eliminado, `close()`→`closeCursor()`. |
| `blog_posts.php` | CRUD de entradas de blog. | `=== TRUE`→`!== false`, `$stmt->error`/`$conn->error`→`errorInfo()[2]`, `affected_rows`→`rowCount()` (2), `close()`→`closeCursor()` (2), `get_result`→`fetch`. |
| `category.php` | CRUD de categorías. | `close()`→`closeCursor()` (3), `error`→`errorInfo()[2]`, `fetch_array`→`fetch`. |
| `column_manager.php` | Gestor de columnas de una tabla; genera código. | 17 conversiones: `num_rows`→`rowCount()`, `fetch_row`→`FETCH_NUM`, `fetch(FETCH_BOTH)` donde se lee `$row[0]`, `=== TRUE`→`!== false` (2), `affected_rows`→`rowCount()` (5), `close()`→`closeCursor()` (3), `error`→`errorInfo()[2]`. **Incluye las cadenas de código PHP que genera.** |
| `crud.php` | Vista CRUD genérica. | `fetch_array`→`fetch`, `=== TRUE`→`!== false`. |
| `editmenu.php` | Edita el orden/estructura del menú. | `$this->conn`→`global $conn`, `fetch_array`→`fetch`. |
| `Galleries.php` | CRUD de galerías. | `or die(...)` eliminado (4), `each()`→`foreach` (2), `fetch_array`→`fetch`, `num_rows`→`rowCount()`. |
| `Gallery.php` | CRUD de galerías (variante extensa). | 26 conversiones: `mysqli_fetch_array`→`fetch`, `or die(...)` eliminado (10), `each()`→`foreach` (4), `close()`/`num_rows`/`error` equivalentes. |
| `Images.php` | CRUD de imágenes de galería. | `affected_rows`→`$stmt->rowCount()` (asignando la query), `or die` eliminado (3), `fetch_array`→`fetch`, `JOIN` conservado. |
| `mailers.php` | Configuración de correo. | `=== TRUE`→`!== false`, `fetch_fields()`→lista de claves PDO, `close()`→`closeCursor()`, `or die` eliminado. |
| `menu.php` | CRUD del menú de navegación. | `=== TRUE`→`!== false` (3), `$conn->error`→`errorInfo()[2]`, `close()`→`closeCursor()` (colocado tras el bucle), `$this->conn`→`global $conn`. |
| `menu_builder.php` | Constructor visual de menús. | `fetch_array`→`fetch(ASSOC)`. |
| `Multimedia.php` | CRUD de multimedia. | `each()` (1), `affected_rows`→`rowCount()`, `or die` (3), `fetch_array`→`fetch`. |
| `pages.php` | CRUD de páginas del CMS. | `insert_id`→`lastInsertId()` (2), `affected_rows`→`rowCount()` (2) **leyéndolo ahora DESPUÉS de `execute()`** (antes se leía antes: bug), `=== TRUE`→`!== false`, `close()`→`closeCursor()` (6), `$this->conn`→`global $conn`. |
| `plugins.php` | Gestión de plugins. | `=== TRUE`→`!== false` (3), `close()`→`closeCursor()`, `$conn->close()` eliminado, `error`→`errorInfo()[2]`, `$this->conn`→`global $conn`. |
| `Press.php` | CRUD de notas de prensa. | `each()` (1), `affected_rows`→`rowCount()`, `or die` (3), `fetch_array`→`fetch`. |
| `Profile.php` | Edición del perfil visual público. | `mysqli_fetch_array($conn->query(...))`→`fetch(ASSOC)`. `connect_errno`/`connect_error` (inexistentes) eliminados y sustituidos por el comentario/patrón PDO coherente. |
| `Profiles.php` | Listado de perfiles. | `affected_rows`→`rowCount()` (2), `or die` (3), `fetch_array`→`fetch`. |
| `search.php` | Buscador del panel. | `fetch_array`→`fetch(ASSOC)`. |
| `search_1.php` | Buscador (variante). | `fetch_assoc`→`fetch(ASSOC)`. |
| `settings.php` | Configuración del sitio (`site_configuration`). | `=== TRUE`→`!== false` (2), `fetch_fields()`→claves PDO (2), `close()`→`closeCursor()`, `error`→`errorInfo()[2]`, `$this->conn`→`global $conn`. |
| `table_config.php` | Configuración de tablas para el CRUD. | `fetch_row`→`fetch(FETCH_NUM)`, `close()`→`closeCursor()`, `$this->conn`→`global $conn`. |
| `table_crud.php` | CRUD genérico por tabla. | `=== TRUE`→`!== false`, `fetch_array`→`fetch`. |
| `table_manager.php` | Gestor de tablas (crear/editar estructura). | 18 conversiones: `mysqli_error()`→`errorInfo()[2]`, `=== TRUE`→`!== false` (3), `fetch_array`/`num_rows`/`close()` equivalentes, `$this->conn`→`global $conn`, guard PDO coherente. **Incluye código PHP generado en cadenas/archivos temporales.** |
| `theme_template.php` | Editor de plantillas de tema. | `=== TRUE`→`!== false`, `affected_rows`→`rowCount()` (2), `close()`→`closeCursor()`, `error`→`errorInfo()[2]`, `fetch_array`→`fetch`. |
| `themes.php` | CRUD de temas. | 16 conversiones. **Bug corregido:** el `UPDATE` tenía `bind_param()` pero **nunca `execute()`**, así que no se ejecutaba; al aplicar el mapeo ahora sí se ejecuta. `$rtp->get_result()` (no existe en PDO) convertido a `fetch`. |
| `TypeBlocks.php` | CRUD de tipos de bloque. | `affected_rows`→`rowCount()` (2), `or die` (3), `fetch_array`→`fetch`. |
| `TypePages.php` | CRUD de tipos de página. | `affected_rows`→`rowCount()` (2), `or die` (3), `fetch_array`→`fetch`. |
| `Videos.php` | CRUD de vídeos. | `affected_rows`→`rowCount()`, `or die` (3), `fetch_array`→`fetch`, `$this->conn`→`global $conn`. |

### 6.3 `core/elements/` — layout del panel (5)

| Archivo | Funcionalidad | Cambios |
|---|---|---|
| `menu.php` | Funciones `getLink()`, `dropdown()` y menús del layout público. | `bind_param`+`get_result`+`fetch_assoc`→`execute([...])`+`fetch(ASSOC)`, `close()`→`closeCursor()` (reubicado tras el bucle), `$this->conn`→`global $conn`, `$rsmenu` convertido a PDO. |
| `menu_post.php` | Menú de categorías de entradas. | `fetch_assoc`/`fetch_array`→`fetch(ASSOC)` (6), `global $conn` en cada función. |
| `sidebar.php` | Barra lateral del panel. | `fetch_array`→`fetch(ASSOC)`. |
| `sidenav.php` | Navegación lateral (páginas y categorías). | `fetch_array`→`fetch(ASSOC)`, `fetch_assoc`→`fetch(ASSOC)`, `$this->conn`→`global $conn`. |
| `top.php` | `<head>` público + carga del tema activo. | `$this->conn` (indefinido según el contexto del include)→`global $conn`, `bind_param`+`get_result`+`num_rows`+`fetch_assoc`→`execute([...])`+`rowCount()`+`fetch(ASSOC)`, `closeCursor()` dentro de la rama `$nt > 0`. |

### 6.4 `core/users/` — área de perfil (16)

| Archivo | Funcionalidad | Cambios |
|---|---|---|
| `personalInfo.php` | Perfil del usuario: datos + carpeta de archivos. | `$this->conn`→`global $conn`; 2 `bind_param`+`get_result`+`fetch_assoc`→`execute([...])`+`fetch(ASSOC)`. |
| `personalInfox.php` | Variante de edición de datos personales. | Igual + `affected_rows`→`rowCount()` (leído tras `execute`). |
| `profiles.php` | Perfil público del usuario. | `get_result`+`fetch_assoc`+`close`→`fetch(ASSOC)`+`closeCursor()`; `fetch_array`→`fetch`. |
| `infodetails.php` | Detalles de información del usuario. | `$conn->error`→`errorInfo()[2]`; `$conn->close()` eliminado; `fetch_array`→`fetch`. |
| `views/info.php` | Formulario de información. | `bind_param`→`execute([...])`, `close()`→`closeCursor()`. |
| `views/locations.php` | Formulario de ubicación. | Idem. |
| `views/privacy.php` | Formulario de privacidad. | Idem. |
| `views/profile.php` | Formulario de perfil. | Idem. |
| `views/verifications.php` | Verificaciones del usuario. | `close()`→`closeCursor()`. |
| `controls/socialmedia.php` | Enlaces a redes sociales. | `bind_param`→`execute([...])`, `fetch_array`/`num_rows`/`close()`→PDO, `$this->conn`→`global $conn`, `closeCursor()` tras el bloque. |
| `controls/update_image.php` | Subida/actualización de la imagen de perfil. | `bind_param`→`execute([...])`, `affected_rows`→`rowCount()` (ahora leído **después** de ejecutar: antes era un valor inválido), `global $conn`. |
| `controls/update_info.php` | Actualiza información personal. | `bind_param`→`execute([...])`, `fetch_array`/`close()`→PDO, `global $conn`. |
| `controls/update_locations.php` | Actualiza ubicación. | Idem. |
| `controls/update_privacy.php` | Actualiza privacidad. | Idem **+ typo corregido**: `$this->conn>prepare`→`$conn->prepare`. |
| `controls/update_profile.php` | Actualiza perfil. | Igual que `update_info.php`. |
| `controls/update_social.php` | Actualiza redes sociales. | `bind_param`→`execute([...])`, `global $conn`. |

### 6.5 `core/templates/` — plantillas públicas (10)

| Archivo | Funcionalidad | Cambios |
|---|---|---|
| `model.php` | Clase `Model` de ejemplo (demo CRUD `tbl_book`). | `new mysqli`+credenciales→`new PDO` con DSN y opciones PDO. `mysqli_fetch_assoc`→`fetch(ASSOC)`, `mysqli_fetch_row`→`fetch(FETCH_NUM)`. La propiedad privada `$connection` se renombró a `$pdo` (privada: la API de la clase no cambia). |
| `home.php` | Plantilla de inicio. | `fetch_array`/`num_rows`→PDO. |
| `contact.php` | Plantilla de contacto. | Idem. |
| `gallery.php` | Plantilla de galería. | Idem. |
| `master.php` | Plantilla maestra. | Idem. |
| `multimedia.php` | Plantilla de multimedia. | Idem. |
| `simple.php` | Plantilla simple. | Idem. |
| `left_sidebar.php` | Plantilla con barra lateral izquierda. | Idem. |
| `right_sidebar.php` | Plantilla con barra lateral derecha. | Idem. |
| `two_sidebar.php` | Plantilla con dos barras laterales. | Idem. |

### 6.6 `core/blocks/` — bloques públicos GrapesJS (43)

Todos siguen el mismo patrón: `$rslideg`/`$rslidet`/`$rslider` (galería, imágenes, slide) y `$result`/`$resultc`.
Conversión aplicada: `fetch_array()`→`fetch(PDO::FETCH_ASSOC)`, `fetch_array()`→`fetch(PDO::FETCH_BOTH)` **sólo donde se lee `$row[0]`**,
`fetch_row()`→`fetch(PDO::FETCH_NUM)`, `fetch_assoc()`→`fetch(PDO::FETCH_ASSOC)`,
`mysqli_fetch_array($conn->query(...))`→`$conn->query(...)->fetch(PDO::FETCH_ASSOC)` (el texto SQL quedó intacto).
Además, `while (list($k,$v) = each($a))` (fatal en PHP 8) → `foreach` en **`multimediabase.php`** y **`profile.php`**.

| Archivo | Bloque | Singularidad |
|---|---|---|
| `about/about.php` | «Acerca de»: `my_info`. | `mysqli_fetch_array($conn->query(...))`→PDO. |
| `about/abouts.php` | Acerca de (variante). | `fetch_assoc`→`fetch(ASSOC)`. |
| `about/aboutx.php` | Acerca de (variante). | `$resultc->fetch_row()`→`FETCH_NUM`; `$rsl->fetch_array()`→`FETCH_ASSOC`. |
| `contact/contact.php` | Contacto: formulario/datos. | `fetch_row()`→`FETCH_NUM`. |
| `contact/contacts.php` | Contacto (variante). | `fetch_assoc`→`fetch(ASSOC)`. |
| `contact/contactx.php` | Contacto (variante). | `fetch_row()`→`FETCH_NUM` + `fetch_array()`→`ASSOC`. |
| `contact/contactxc.php` | Contacto (variante con envío). | `fetch_row()`→`FETCH_NUM`. |
| `content/content.php` | Bloque de contenido libre. | `fetch_assoc`→`fetch(ASSOC)`. |
| `galleries/galleries.php` | Galería con miniaturas. | — |
| `galleries/galleries1.php` | Galería (variante 1). | — |
| `galleries/x.php` | Galería (variante). | — |
| `galleriesz/galleries.php` | Galería (set z). | — |
| `galleriesz/galleries1.php` | Galería z (variante). | — |
| `gallery/gallery.php` | Galería simple. | — |
| `gallerycontent/gallerycontent.php` | Galería + contenido. | — |
| `gallerycontent/gallerycontentbase.php` | Base de galería+contenido. | — |
| `galleryslide/galleryslide.php` | Galería con carrusel. | — |
| `galleryslide/galleryslidebase.php` | Base de galería-carrusel. | — |
| `galleryslide/galleryslidebackup.php` | Respaldo de galería-carrusel. | — |
| `galleryvideo/galleryvideo.php` | Galería de vídeo. | — |
| `galleryvideo/galleryslidebase.php` | Base de galería de vídeo. | — |
| `homegal/homegal.php` | Galería de inicio. | — |
| `homegal/homegal1.php` | Galería de inicio (variante). | — |
| `multimedia/multimedia.php` | Bloque multimedia. | — |
| `multimedia/multimediabase.php` | Base multimedia. | `FETCH_BOTH` en las filas leídas con `$rowt[0]`/`$row[0]`; **`each()`→`foreach`**. |
| `multimedia/multimediabyframe.php` | Multimedia por marco. | Idem `FETCH_BOTH`. |
| `multimedia/multimediaold.php` | Multimedia (versión antigua). | — |
| `portafolio/portafolio.php` | Portafolio de proyectos. | `FETCH_BOTH` en las filas leídas con `$rowt[0]`. |
| `press/press.php` | Notas de prensa. | — |
| `pressgallery/pressgallery.php` | Galería de prensa. | — |
| `pressgallery/pressgallerybase.php` | Base de galería de prensa. | — |
| `prints/prints.php` | Bloque de impresiones. | `mysqli_fetch_array($conn->query(...))`→PDO. |
| `prints/galleryslidebase.php` | Base de impresiones-carrusel. | — |
| `prints/galleryslidebackup.php` | Respaldo. | — |
| `profile/profile.php` | Ficha de perfil público. | `mysqli_fetch_array(...)`→PDO; `fetch_row()`→`FETCH_NUM`; **`each()`→`foreach`**. |
| `simple/simple.php` | Bloque simple de texto. | `fetch_assoc`→`fetch(ASSOC)`. |
| `single/single.php` | Bloque individual. | — |
| `single/singlebase.php` | Base individual. | — |
| `slide/slide.php` | Carrusel de diapositivas. | — |
| `slide/slidebase.php` | Base del carrusel. | — |
| `slidegallery/slidegallery.php` | Galería-carrusel. | — |
| `slidegallery/slidegallerybase.php` | Base galería-carrusel. | — |
| `video/video.php` | Bloque de vídeo (lee `page`). | `mysqli_fetch_array($conn->query(...))`→PDO (SQL con espacio final intacto). |

---

## 7. Hallazgos de coherencia y deuda técnica pendiente

Detectados al verificar. **No** se corrigieron en esta migración salvo donde se indica, porque exceden el alcance
«MySQLi → PDO» o requieren una decisión de producto/base de datos.

### 7.1 🔴 Bloqueante previo, ajeno a la migración
- **`core/controller/test1.php:77`** contiene un marcador sin completar: `$user = [...];` → **`Parse error`**.
  El archivo **no compila** y no se puede cargar. Es el único fallo de `php -l` en `core/`. Hay que escribir el código real.

### 7.2 🟠 Bugs lógicos que siguen ahí (no son de mysqli)
| Archivo | Problema |
|---|---|
| `core/users/views/{info,locations,privacy,profile,verifications}.php` | Llaman `$conn->prepare($sql)` pero definen la variable `$query` (queda `$sql` indefinido) y usan un `$id` inexistente. |
| `core/users/controls/update_locations.php` | El `UPDATE` está **sin `SET`**. |
| `core/elements/sidenav.php` (~línea 272) | Usa `$poste` en lugar de `$post`. |
| `core/managers/updatetmp.php` | ✅ **Rectificado y resuelto.** Se documentó que `$id` "nunca se define", pero es **incorrecto**: el archivo es un *include* y `$id` lo aporta su único incluirdor, `core/managers/views/crud.php:128` (`$id = $_GET["id"]`). Para que no dependa del contexto de inclusión se resolvió de forma explícita (`$id ?? $_GET['id']`) con guarda. |
| `core/blocks/about/about.php:4` | `count($row)` después del fetch: si no hay filas, `$row === false` y `count(false)` es **`TypeError` en PHP 8**. |
| `core/managers/session.php` | Incluye `database.php`, `mailer.php` (**inexistente**) y `form.php`. Sistema de login legado (Jpmaster77); probablemente código muerto. |
| `core/managers/tt.php`, `pagination.php`, `testorder/*` | Demos/legado; usan tablas (`students`, `items`, `table_queries`) que pueden no existir. `tt.php` y `testorder/*` no tienen referencias en el proyecto. |

**Bugs corregidos como efecto colateral del mapeo** (merecen prueba funcional):

| Archivo | Antes | Ahora |
|---|---|---|
| `core/managers/views/themes.php` | `UPDATE` con `bind_param()` pero **sin `execute()`**: nunca se guardaba. | Se ejecuta. |
| `core/managers/views/pages.php` | `affected_rows` leído **antes** de `execute()`. | `rowCount()` después de `execute()`. |
| `core/users/controls/update_image.php` | `affected_rows` leído antes de `execute()`. | `rowCount()` después. |
| `core/users/controls/update_privacy.php` | Typo `$this->conn>prepare`. | `$conn->prepare`. |
| `core/application/installer/install.php` | Generaba `dbconnection.php` con `$link->MysqliConnection()` (método inexistente). | Genera `$link->getConnection()`. |

### 7.3 🟡 Inyección SQL (deuda estructural)
La migración fue **fiel**: no se reescribió el SQL. Sigue habiendo concatenación/interpolación de `$_POST`/`$_GET`/`$id`
en el texto SQL (p. ej. `core/managers/views/*`, `core/managers/app.php`, `core/config/function.php`).
**Mejora recomendada:** sustituir esas consultas por `prepare()` + `execute([...])` de forma progresiva,
empezando por los formularios de escritura (INSERT/UPDATE/DELETE).
`protect()` **no** es una defensa válida para esto.

### 7.4 🟡 `getColumnMeta()` vs claves del row
En `core/managers/views/settings.php` y `mailers.php` se sustituyó `fetch_fields()` por la lista de claves del
primer registro (`array_keys($row)`). Es equivalente para el uso actual (`$val->name`), **pero** si la consulta
devuelve 0 filas la lista de columnas queda vacía (antes `fetch_fields()` sí listaba columnas).
Si se necesita la lista real de columnas sin filas, usar `getColumnMeta()` como en `tbq.php`/`tbn.php`.

### 7.5 🟢 Diferencia de charset entre instalador y runtime — RESUELTO
El `server.php` que genera el instalador escribía `'charset' => 'utf8'` (para las conexiones
`cms` y `ecommerce`), mientras `Database` hacía `SET NAMES utf8mb4`. `utf8` en MySQL **no es
UTF-8 real** (no admite 4 bytes: emoji, ciertos CJK…).

**Corregido:** el instalador genera ahora `'charset' => 'utf8mb4'` en ambas conexiones.
Además `PepiuoX\Database\Connection::sanitizeCharset()` valida el valor contra una lista
blanca (`utf8mb4`, `utf8`, `latin1`, `ascii`) y por defecto usa `utf8mb4`.

### 7.6 🟡 `Database::PdoConnection()` / `MysqliConnection()`
`core/config/Database.php` expone `getConnection()` (canónico) y `PdoConnection()` (alias).
Ya **no existe** ningún `MysqliConnection()` en el repositorio. Si conservas `PdoConnection()` sólo por
compatibilidad, documéntalo aquí; si nadie lo usa, elimínalo.

### 7.7 🟢 elFinder sigue en MySQLi (por diseño)
`core/filemanager/php/elFinderVolumeMySQL.class.php` mantiene su driver MySQLi interno (librería de terceros).
No participa de la conexión PDO. Requiere actualizar la librería completa si se quiere migrar.

### 7.8 🟢 `elFinderVolumeDropbox` ya usaba PDO
`core/filemanager/php/elFinderVolumeDropbox.class.php` usa `new PDO(...)` desde antes. No se tocó.

---

## 8. Verificación e historial

### 8.1 Verificación ejecutada

| Prueba | Resultado |
|---|---|
| `php -l` sobre los **554** `.php` de `core/` | **553 OK**, 1 fallo previo ajeno (`core/controller/test1.php`, §7.1) |
| `php -l` individual de los 125 archivos modificados | **0 errores de sintaxis** |
| Escaneo residual (`mysqli_`, `MYSQLI_`, `fetch_array(`, `fetch_assoc(`, `fetch_row(`, `fetch_object(`, `num_rows`, `get_result(`, `bind_param(`, `real_escape_string`, `affected_rows`, `insert_id`, `fetch_field(`, `field_count`, `connect_error`, `$conn->error`, `$conn->close()`, `$stmt->close()`) | **Sin coincidencias** salvo comentarios explicativos |
| Búsqueda de `MysqliConnection` / `mysql_connect` | **Sin coincidencias** |
| Revisión `git diff` por archivo | Sólo cambios de API; SQL, HTML, CSS, JS y nombres de variables intactos |

### 8.2 Cambios de alcance mínimo verificados

- **No** se reformatearon archivos; los diffs son quirúrgicos (≈900 inserciones / ≈926 supresiones).
- **No** se cambió texto SQL, nombres de tabla/columna, ni estructura HTML/JS.
- **No** se convirtieron a `prepare()` las consultas con interpolación (§7.3, queda como mejora).

### 8.3 Historial

| Fecha | Cambio | Alcance |
|---|---|---|
| — | Migración inicial MySQLi → PDO | 125 archivos: `config`, `application/installer`, `managers` (19 + 29 vistas), `elements` (5), `users` (16), `templates` (10), `blocks` (43) |

---

## 9. Cómo usar este documento

1. **Antes de tocar** un archivo de `core/` que consulte la BD: busca el archivo en §6 para saber qué se cambió y por qué.
2. **Antes de escribir código nuevo:** copia el patrón de §3 y respeta §2.
3. **Al terminar un cambio:** pasa el escaneo de §3 (debe salir limpio).
4. **Si añades un archivo o cambias un mapeo:** actualiza §6 y/o §2 y añade una línea a §8.3.
5. **Si vas a resolver un punto de §7:** muévelo al historial (§8.3) y ajusta el inventario.

---

## 10. Arquitectura de carga: namespaces y autoload

### 10.1 Un único autoloader

Antes había **cinco** archivos distintos declarando `class Autoload` (raíz, `core/`, `core/managers/`,
`core/pages/`, `core/users/`, `core/application/installer/`). Cargar dos cualesquiera producía
`Cannot declare class Autoload, because the name is already in use` (error fatal). Además:

- `core/Autoload.php` apuntaba a `core/core/controller/` (ruta inexistente).
- `core/managers/Autoload.php`, `core/pages/Autoload.php` y `core/users/Autoload.php` apuntaban a
  `classes/` (inexistente).
- La raíz usaba una ruta **relativa al directorio de trabajo**.
- `core/config/loader.php` exigía `core/config/Autoload.php`, que **no existía** → `RuntimeException`
  en cada petición.
- Todos lanzaban `Exception` por cualquier clase ajena (PHPMailer, elFinder…).

Ahora existe **uno solo**: `core/Autoload.php` → `PepiuoX\Autoloader\ClassLoader`.
Los otros cinco archivos son *shims* que lo requieren (se conservan para no romper `require`s existentes).

### 10.2 Orden de resolución

| # | Tipo | Regla | Ejemplo |
|---|---|---|---|
| 1 | **Código nuevo (PSR-4)** | `PepiuoX\` → `core/src/` | `PepiuoX\Database\Connection` → `core/src/Database/Connection.php` |
| 2 | **Puente de compatibilidad** | `COMPAT_MAP` (clases globales fuera de `core/controller/`) | `Database` → `core/config/Database.php` |
| 3 | **Clases legadas globales** | `core/controller/<Nombre>.php`, resuelto con un índice **insensible a mayúsculas** | `UsersClass`, `userForgot` → `UserForgot.php` |

Reglas de diseño del autoloader:

- **Nunca lanza excepciones**: si la clase no es del proyecto, devuelve el control a los demás
  autoloaders (Composer, PHPMailer, elFinder).
- **Idempotente**: `register()` puede llamarse N veces.
- **Independiente del CWD** y de Composer.

### 10.3 Cómo se escribe código nuevo

```php
// core/src/Factory/PageFactory.php
declare(strict_types=1);

namespace PepiuoX\Factory;

use PepiuoX\Database\Connection;

final class PageFactory
{
    public function __construct(private Connection $db) {}
}
```

```php
// Cualquier punto de la aplicación (ya autoloaded):
use PepiuoX\Factory\PageFactory;

$factory = new PageFactory(new Connection());
```

### 10.4 El shim `Database`

`core/config/Database.php` **ya no contiene lógica**: requiere la clase namespaced y crea un alias.

```
Database  ⇢  PepiuoX\Database\Connection      (conexión canónica, singleton, getConnection())
App\Core\Database  ⇢  core/controller/Database.php  (wrapper con DSN; sin uso en la aplicación)
```

Motivo: había dos clases con nombre `Database` en el proyecto y la resolución dependía del **orden de
carga** (funcionaba sólo porque `dbconnection.php` se incluye pronto). El `COMPAT_MAP` lo hace explícito.

`PepiuoX\Database\Connection` además **comparte realmente el PDO** entre todas las instancias
(antes cada `new Database()` abría una conexión nueva; había 28 puntos que la instanciaban).

### 10.5 Duplicados de clase neutralizados

Estos archivos declaraban una clase con el **mismo nombre** que otro archivo (fatal si ambos se cargan)
o con un nombre **distinto al del archivo** (imposible de autoloadear). Se renombró la clase para que
coincida con el nombre del archivo, sin borrar nada:

| Archivo | Declaraba | Ahora declara |
|---|---|---|
| `core/controller/AccessConnect1.php` | `AccessConnect` | `AccessConnect1` |
| `core/controller/AccessLevel1.php` | `AccessLevel` | `AccessLevel1` |
| `core/controller/UsersCodeAccess1.php` | `UsersCodeAccess` | `UsersCodeAccess1` |
| `core/controller/UsersCompare1.php` | `UsersCompare` | `UsersCompare1` |
| `core/controller/UsersFollows1.php` | `UsersFollows` | `UsersFollows1` |
| `core/controller/UsersLikes1.php` | `UsersLikes` | `UsersLikes1` |
| `core/controller/UsersNotes1.php` | `UsersNotes` | `UsersNotes1` |
| `core/controller/UserForgot.php` | `UsersForgot` | `UserForgot` |

Ninguno de estos `*1.php` estaba referenciado en el proyecto. `UserForgot.php` lo usan
`core/pages/forgot/*` y `core/pages/recover/*`, que lo invocaban como `new userForgot()`
(la clase se llamaba `UsersForgot`) → **error fatal**. Ahora nombre de archivo, clase y llamada coinciden.

> ⚠️ `core/pages/gallery.php` y `core/users/filemanager.php` (librería de terceros *files.photo.gallery*)
> declaran **ambos** `class config`. Si se cargan en la misma petición, es fatal. No se toca por ser
> código vendorizado; queda documentado como riesgo conocido.

### 10.6 `composer.json`

```json
"autoload": {
    "psr-4": { "PepiuoX\\": "core/src/" },
    "classmap": ["core/controller/"],
    "files": ["core/Autoload.php"]
}
```

`require.php` pasa de `^8.2` a **`^8.3`** (8.3 y 8.4 verificados).

### 10.7 Cómo migrar una clase legada a namespace (progresivo)

1. Mover el archivo a `core/src/<Área>/<Clase>.php`.
2. Añadir `declare(strict_types=1);` y `namespace PepiuoX\<Área>;`.
3. Actualizar sus referencias (`new Clase()` → `use PepiuoX\<Área>\Clase;`).
4. Si queda código externo sin migrar, añadir un shim con `class_alias()` en la ubicación antigua.
5. Ejecutar `php -l` y la lista de comprobación de §3, y **actualizar este documento**.

### 10.8 Otras correcciones de arranque

| Archivo | Problema | Corrección |
|---|---|---|
| `core/config/loader.php` | Exigía `core/config/Autoload.php` (inexistente) → excepción en cada petición | Carga `core/Autoload.php`; rutas normalizadas |
| `core/config/dbconnection.php` | `include` del shim (podía redeclarar el alias) | `require_once` + autoloader primero |
| `core/dispatcher.php` | `new Routers()`, `new GetVisitor()`, `new PagePublic()`, `new PageSystem()` sin el `PDO` obligatorio → `ArgumentCountError` fatal | Se pasa `$conn` a las cuatro |
| `core/index.php` | `header("Location: $site")` con `$_SERVER['HTTP_HOST']` → *host header injection* / open redirect | Ver §7 del informe de seguridad |
