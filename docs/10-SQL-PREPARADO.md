# 10 — SQL preparado y validación de identificadores

## Regla del proyecto

PDO **no permite enlazar identificadores** (tabla, columna, `SHOW COLUMNS ... FROM`). Por tanto:

- **Valores**: siempre marcadores (`:param` o `?`), nunca concatenación ni interpolación.
- **Identificadores**: siempre validados con `PepiuoX\Security\SqlIdentifier::assert()` (solo
  `[A-Za-z_][A-Za-z0-9_]*`, máximo 64) o con una lista blanca explícita.

Esta distinción es la que separa un refactor real de un `preg_replace` cosmético.

## Instrumento

`tools/scan-sql.php` recorre el código PDO y clasifica cada consulta construida dinámicamente:

| Categoría | Significado | Acción |
|---|---|---|
| `valor` | Un valor entra en el texto SQL | Debe pasar a marcador |
| `identificador_sin_validar` | Tabla o columna interpolada sin validación | Riesgo: validar |
| `identificador_derivado` | Construida con piezas ya validadas (joins, `?,?,?`) | Informativa |
| `identificador_validado` | Pasa por `SqlIdentifier` o por `validateIdentifier()` | Correcta |

## Estado medido

| Momento | valor | sin validar | derivado | validado |
|---|---|---|---|---|
| Antes del refactor | 9 | 39 | — | 47 |
| Después del refactor | 15 | 11 | 2 | 75 |

El total sube porque el detector es más fino: ahora reconoce `validateIdentifier()` y la
asignación desde literal, y separa los agregados derivados. Prueba de que el aumento no es
trabajo pendiente: **de las 15 coincidencias de `valor`, ninguna es una consulta ejecutada con
datos del usuario**. Seis están en generadores de código y nueve en las herramientas de
`tools/` (que construyen la consulta con nombres de tabla constantes o ya validados).

## Cambios aplicados

| Archivo | Cambio |
|---|---|
| `core/config/function.php` | `WHERE Field = '<variable>'` → `WHERE Field = :field` con `prepare()`/`execute()`. Era el único punto donde un **valor** entraba en el texto SQL |
| `core/controller/FileManager.php` | Valida `$this->table_name` en el constructor |
| `core/controller/ViewCart.php` | Conexión opcional (`Connection::shared()`) y valida `$this->tableName` |
| `core/controller/PageRepository.php` | Conexión opcional y valida `$this->table` |
| `core/controller/AddCart.php` | Conexión opcional y valida `$this->table_name` |
| `core/controller/SiteDefinitions.php` | Sustituye su expresión regular propia por `SqlIdentifier::assert()`: una sola regla de validación en todo el proyecto |
| `core/controller/MyCRUD.php` | `getID()` valida el nombre de columna que devuelve la metadata antes de interpolarlo (cubre las 4 consultas que lo usan) |
| `tools/inspect-db.php` | Valida los nombres de tabla antes de `SELECT * FROM` y `SHOW INDEX` |

## Revisados y aceptados sin cambios

| Archivo | Motivo |
|---|---|
| `core/managers/vuejs_crud.php` | Es un **generador** de código: emite `$stmt = $conn->prepare(...)` con marcadores (`:col`) en `INSERT`/`UPDATE`/`DELETE`. Sus nombres de tabla, columnas y cláusulas `WHERE`/`JOIN` ya pasan por `SqlIdentifier::assert()`/`fromWhitelist()` |
| `core/managers/views/column_manager.php` | También generador: produce `$sqlN = "UPDATE ..."; $stmtN = $conn->prepare($sqlN);` con `:query` |
| `core/controller/PageCacheManager.php` | Interpola `$placeholders`, que vale literalmente `?,?,?` |
| `core/managers/views/menu.php`, `plugins.php` | `$tble` se valida al asignarlo desde un literal (`SqlIdentifier::assert('menu_options')`) |
| `core/controller/DatabaseCacheDriver.php` | `$this->table` se valida en el constructor |
| `core/controller/PageRepository.php`, `UserManager.php`, `ActivityLogger.php` | Ya validaban; ahora además en el constructor |

## Deuda declarada

Los dos generadores (`vuejs_crud.php`, `column_manager.php`) escriben archivos PHP en el
proyecto. Su salida es correcta hoy, pero cualquier cambio futuro en esos generadores puede
reintroducir concatenación sin que nadie lo note. Recomendación: añadir una prueba que genere
en un directorio temporal y pase `tools/scan-sql.php` sobre el resultado.