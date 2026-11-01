# 08 — Estado del proyecto

**Proyecto:** PHP-GrapesJS — CMS PHP 8 + MariaDB con constructor de páginas GrapesJS.
**Ruta:** `C:\Users\PePiuoX\Documents\Github\PHP-GrapesJS`
**Fecha del estado:** 2026-10-17
**Estado de la entrega:** **VALIDADO CON LIMITACIONES**

## Objetivo de la intervención

Analizar y verificar las funcionalidades del CMS para poder corregirlo, documentando el proceso
en `docs/`, con pruebas en `tests/` e instrumentos en `tools/`, respaldando la base de datos
antes de cualquier cambio y sin tocar `vendor/`, `plugin/`, `build/assets/`, `build/themes/` ni
`build/uploads/`.

## Requisitos verificados

| Requisito | Estado | Evidencia |
|---|---|---|
| Acceso con correo electrónico + contraseña + PIN | Cumple | `tools/verify-login-http.php` 8/8 |
| El identificador del usuario cambia en cada ingreso | Cumple | Rotación verificada y propagada por FK |
| La portada y las páginas públicas se renderizan | Cumple | `GET /` → 200; 6 páginas en `pages` |
| Las rutas del sistema responden (acceso, registro, recuperación, perfil) | Cumple | `tools/http-smoke.php` 8/8 |
| El panel de administración exige sesión y rol | Cumple | `/admin/dashboard` → 403 sin sesión |
| Cierre de sesión efectivo | Cumple | Perfil → 302 tras la salida |
| Sintaxis válida en todo el árbol PHP | Cumple | 558 archivos, 0 errores |
| Respaldo de base de datos disponible | Cumple | 2 volcados de 115 tablas |

Requisitos **no** cumplidos todavía (documentados, no aplicados):

- Contraseñas y PIN con hash irreversible (H-09, H-10).
- Secretos fuera del repositorio y rotados (H-07, H-08).
- Registro coherente con el acceso (H-11).
- Páginas de error 403 y 500 (H-18).

## Actores y permisos observados

| Actor | Acceso |
|---|---|
| Visitante | Páginas públicas de `pages` con `view_page = public` |
| Usuario registrado | `profile/*` (requiere `user_id` en sesión) |
| Administrador | `admin/*` (requiere `levels` ∈ {admin, master}) |
| Instalador | `core/application/installer/*` mientras no exista `core/config/server.php` |

Roles en base: `users_roles` (5), `roles` (3), `permissions` (15), `users_role_permissions` (27).

## Arquitectura aprobada en esta intervención

Ver [02-ARQUITECTURA.md](02-ARQUITECTURA.md) y [07-DECISIONES-ADR.md](07-DECISIONES-ADR.md)
(ADR-001 a ADR-006). Resumen: se mantiene el diseño de dos routers, el autoloader único y la
inyección de PDO como camino preferente, con resolución por `Connection::shared()` solo para el
código heredado que no puede recibirla.

## Modelo de datos

Ver [02-ARQUITECTURA.md § Modelo de datos](02-ARQUITECTURA.md). Sin cambios de esquema en esta
intervención: **no se ejecutó ninguna migración ni se alteró ninguna tabla**.

## Archivos creados

    docs/00..08                              esta documentación
    tools/DiAudit.php  tools/audit-di.php    auditoría de inyección de dependencias
    tools/lint.php                           verificación de sintaxis
    tools/scan-risk.php                      patrones de riesgo
    tools/inspect-db.php                     inspección de solo lectura
    tools/backup-db.php                      respaldo lógico
    tools/verify-login.php                   verificación de datos del acceso
    tools/verify-login-http.php              verificación HTTP del acceso
    tools/http-smoke.php                     prueba de humo
    tools/patch-di.php tools/patch-crypto.php tools/patch-login.php
    tools/patch-id-casts.php tools/patch-credentials.php
    tools/reports/                           informes generados
    tests/bootstrap.php tests/Unit/* tests/Architecture/*
    storage/backups/                         respaldos de base y de archivos originales

## Archivos modificados

`core/src/Database/Connection.php`, `core/src/Security/SqlIdentifier.php`,
`core/controller/{UsersClass,Routers,GetVisitor,RecoveryPhrase,ChangePass,UsersForgot,
UsersCodeAccess,Protect,BlogPost,CheckValidUser,SendData,UserVerify,Visitors,UserManager,
NewUsers,NewscpUsers,UsersUpdates,UsersVerify,newUser,ChangePIN,CheckUsersSession,userChange,
UserForgot,UsersChange,GetCodeDeEncrypt,PagePublic}.php`,
`core/application/installer/installUser.php`, `build/index.php`.

## Pruebas ejecutadas

Ver [06-PRUEBAS-Y-EVIDENCIAS.md](06-PRUEBAS-Y-EVIDENCIAS.md): 558 archivos sin errores de
sintaxis, 0 incompatibilidades de inyección de dependencias, 23 pruebas PHPUnit OK, 15/15 pasos
de datos, 8/8 rutas HTTP, 8/8 pasos de acceso real.

## Defectos pendientes

H-07, H-08, H-09, H-10, H-11, H-14, H-16, H-18, H-19, H-20, H-21 — ver
[04-HALLAZGOS.md](04-HALLAZGOS.md).

## Aprobaciones recibidas

- 2026-10-17: el usuario confirma que el proyecto objetivo es `PHP-GrapesJS` y autoriza el
  análisis, la documentación y las correcciones con las carpetas de terceros excluidas.

## Riesgos

1. **Seguridad de credenciales (crítico).** Claves de cifrado y credenciales de base de datos
   están versionadas en el repositorio. Hasta rotarlas, cualquier copia del repositorio permite
   descifrar datos de usuario y conectarse a la base.
2. **Datos mixtos.** Conviven filas cifradas en CBC (legibles y buscables) y en GCM (legibles
   pero no buscables). Un usuario creado con el formato GCM no se localiza por igualdad: solo se
   resolvería con índice ciego o re-cifrado.
3. **Registro roto.** El alta por `newUser` guarda en claro y el acceso busca cifrado (H-11).
4. **Deuda estructural.** Más de un 20 % de los archivos de `core/` parecen duplicados o sin uso
   (H-20): el riesgo es corregir la copia equivocada.

## Próximos pasos recomendados (en orden)

1. Rotar las credenciales de MariaDB y las claves `SECURE_TOKEN`/`SECURE_HASH`, con re-cifrado
   planificado y respaldo previo (H-07, H-08).
2. Corregir la escritura del registro (`newUser`) para que sea coherente con el acceso (H-11).
3. Migrar contraseñas y PIN a `password_hash` con rehash progresivo y añadir una columna de
   índice ciego (HMAC) para volver al cifrado aleatorio (H-09, H-10).
4. Añadir `core/pages/error_pages/403.php` y `500.php` (H-18).
5. Reparar `phpstan.neon` y ejecutar análisis estático por capas, empezando por `core/src`.
6. Retirar duplicados y código muerto con inventario previo y pruebas (H-20).
7. Revisar la política de sanitización del contenido del builder (H-16) y el bloqueo por
   User-Agent del `.htaccess` (H-21).
---

# Fase 2 — registro, rotación de claves y SQL preparado (2026-10-17)

**Estado de la entrega (fase 2): VALIDADO CON LIMITACIONES**

## Qué se ha hecho en esta fase

| Trabajo | Resultado verificado |
|---|---|
| Registro de nuevos usuarios | El alta crea el usuario con los datos cifrados en el formato que exige el acceso, con sus filas de activación, perfil y rol, en una transacción. `tools/verify-registration.php`: 20/20 PASS y la base queda limpia |
| Plan de rotación de claves | Documentado en [09](09-PLAN-ROTACION-CLAVES.md) e instrumentalizado en `tools/rotate-keys.php` (inventario real ejecutado; `--apply` exige confirmación y respaldo reciente). **No ejecutado** |
| SQL preparado | Refactor documentado en [10](10-SQL-PREPARADO.md); informe reproducible en `tools/reports/scan-sql.md` |
| Hallazgos nuevos | H-22 a H-26 (ver más abajo) |

## Archivos modificados en la fase 2

`core/controller/newUser.php` (registro completo), `core/Autoload.php` (idempotencia y carga de
Composer), `core/config/function.php`, `core/controller/{FileManager,ViewCart,PageRepository,AddCart,SiteDefinitions,MyCRUD}.php`,
`tools/{scan-sql,rotate-keys,verify-registration,patch-registration,patch-sql-prepared}.php`.

Herramientas nuevas: `tools/scan-sql.php`, `tools/rotate-keys.php`, `tools/verify-registration.php`,
`tools/patch-registration.php`, `tools/patch-sql-prepared.php`.

## Hallazgos nuevos

| ID | Severidad | Hallazgo | Estado |
|---|---|---|---|
| H-22 | Crítica 9.1 | El alta insertaba en columnas y tablas inexistentes (`banned`, `profiles`): toda la ruta de registro terminaba en excepción | Corregido |
| H-23 | Alta 7.5 | El alta guardaba usuario, correo y PIN en claro: incompatible con el acceso; nadie podía entrar tras registrarse | Corregido |
| H-24 | Alta 7.5 | `PHPMailer` no se cargaba nunca (el autoloader no incluía `vendor/autoload.php` y la ruta estaba mal calculada): los correos del sistema fallaban con "Class not found" | Corregido |
| H-25 | Media 5.3 | `core/Autoload.php` moría con "Cannot redeclare class" si Composer lo volvía a incluir | Corregido |
| H-26 | Media 5.3 | Credenciales SMTP (`site_security.USEREMAIL`/`PASSMAIL`) almacenadas en claro | Pendiente (se cifran en la rotación) |

## Pruebas de la fase 2

| Verificación | Resultado |
|---|---|
| Sintaxis (`tools/lint.php`) | 560 archivos, 0 errores |
| PHPUnit | 23 pruebas, 72 aserciones, OK |
| Inyección de dependencias | 0 incompatibilidades |
| Escáner SQL | 15 `valor` (6 en generadores, 9 en herramientas propias), 11 `identificador_sin_validar` (limitación del detector: `$table` → `$this->table`), 75 validados |
| Cadena de acceso (datos) | 15/15 PASS |
| Acceso real por HTTP | 8/8 PASS |
| Humo HTTP | 8/8 rutas |
| Alta de usuario | 20/20 PASS, con limpieza |

## Pendiente tras la fase 2

1. Autorizar y ejecutar la rotación de claves ([09](09-PLAN-ROTACION-CLAVES.md)).
2. Sacar `core/config/server.php` y `.env` del control de versiones y rotar la contraseña de MariaDB.
3. Migrar contraseñas y PIN a `password_hash` + índice ciego (H-09, H-10).
4. Verificación por correo del alta: hoy `REQUIRE_EMAIL_VERIFICATION = false` en `newUser`; para
   activarla hay que exponer la ruta de activación (hoy no está en la lista blanca de `PageSystem`).
5. Páginas 403/500, `phpstan.neon` y limpieza de duplicados (H-18, H-19, H-20).