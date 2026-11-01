# 02 — Arquitectura real

## Arranque de una petición

    petición HTTP
      └─ .htaccess (raíz)          reescribe todo a build/ y bloquea firmas de ataque
           └─ build/index.php      define URL = raíz del proyecto; arranca sesión
                └─ core/dispatcher.php
                     ├─ core/config/loader.php
                     │    ├─ core/Autoload.php        autoloader único (PSR-4 + clases legadas)
                     │    ├─ PepiuoX\Security\SessionConfig::start()
                     │    └─ core/config/dbconnection.php
                     │         ├─ core/config/Database.php   shim → PepiuoX\Database\Connection
                     │         ├─ $conn = PDO compartido
                     │         ├─ core/config/function.php
                     │         ├─ core/config/define.php     constantes del sitio
                     │         └─ core/config/language/lang/<es|en>.php
                     ├─ new Routers($conn)      decide si la URL es una página del CMS
                     ├─ new GetVisitor($conn)   contabiliza visitas
                     ├─ new PagePublic($conn)   renderiza páginas públicas
                     ├─ new PageSystem($conn)   renderiza páginas del sistema
                     └─ $rp = $pages->InitPage()

## Dos routers conviviendo

**Routers** (`core/controller/Routers.php`) resuelve las páginas almacenadas en la tabla
`pages` (columnas `link`, `startpage`, `parent`, `active`, `view_page`, `type`). Si la URL
corresponde a una página pública, `PagePublic` la renderiza con los elementos de
`core/elements/`.

**PageSystem** (`core/controller/PageSystem.php`) atiende las rutas del sistema mediante una
lista blanca estricta:

| Sección | Rutas permitidas | Archivo |
|---|---|---|
| `signin` | login, logout, register, forgot-username, forgot-password, forgot-email, forgot-pin | `core/pages/...` |
| `admin` | dashboard, builder | `core/managers/...` |
| `profile` | userprofile, settings, security, activity | `core/users/...` |
| `error` | 404, 403, 500 | `core/pages/error_pages/...` |

Los archivos de `core/pages/`, `core/managers/` y `core/users/` se incluyen **dentro de métodos**
de `PageSystem`. Esa decisión (incluir plantillas en lugar de instanciar controladores) es la
causa raíz del hallazgo H-01: dentro del método no existe `$conn`.

## Autoloader

`core/Autoload.php` resuelve, en este orden: clases nuevas con namespace `PepiuoX\` → `core/src/`;
el mapa de compatibilidad `COMPAT_MAP` (`Database`, `SecurityAccess`, `SessionClass`,
`RemoteAddress`, `PageStatsRecorder`); y las clases legadas de `core/controller/` mediante un
índice insensible a mayúsculas. Nunca lanza excepciones: devuelve el control al siguiente
autoloader.

## Capa de datos

- `core/src/Database/Connection.php` — singleton PDO con `ERRMODE_EXCEPTION`,
  `EMULATE_PREPARES = false`, `utf8mb4` validado por lista blanca y zona horaria `+00:00`.
- `core/config/Database.php` — shim `class_alias` para el código legado (`new Database()`).
- Credenciales en `core/config/server.php`; cifrado del sitio en `site_security` y en
  `core/config/define.php`.

## Modelo de datos relevante (base real `phpgrapesjs`)

| Tabla | Filas | Papel |
|---|---|---|
| `uverify` | 1 | Credenciales de acceso: `iduv`, `usercode`, `username`, `email`, `password`, `mkpin`, `mktoken`, `mkkey`, `mkhash`, `level`, `is_activate`, `is_banned` |
| `users` | 1 | Ficha del usuario; `idUser` referencia `uverify.iduv` con `ON UPDATE CASCADE` |
| `users_profiles` | 1 | Perfil; `idp` → `uverify.iduv` (`ON UPDATE CASCADE`) y `mkhash` de sesión |
| `users_active` | 1 | Estado de activación por `usercode` |
| `site_security` | 1 | `SECURE_TOKEN` y `SECURE_HASH` (claves del sitio) + configuración de correo |
| `pages` | 6 | Páginas del CMS (portada `inicio` + 5 secciones, todas `view_page = public`) |
| `ip`, `login_attempts` | 5 / 0 | Intentos de acceso y bloqueos |

Hay 31 claves foráneas declaradas con `ON UPDATE CASCADE` sobre `uverify.iduv`, lo que hace que
la rotación del identificador se propague a `users`, `users_profiles`, `users_info`, `users_csp`,
`users_privacy` y `users_secures` de forma atómica.

Formato de identificadores: texto hexadecimal de 64 caracteres (`char(128)` en el esquema). Por
eso cualquier `(int)` sobre esos valores los reduce a `0` (hallazgo H-04).

## Estructura de carpetas (equivalencias pedidas)

| En este proyecto | Equivale a | Función |
|---|---|---|
| `build/` | `public/` | Document root: `index.php`, `.htaccess`, estáticos |
| `core/` | `app/` | Aplicación: clases, acciones, controladores y plantillas |
| `vendor/` | `vendor/` | Dependencias Composer (excluida) |